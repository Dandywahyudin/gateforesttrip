<?php

namespace App\Http\Controllers\Wisatawan;

use App\Models\Jadwal;
use App\Models\PaketTrip;
use App\Models\PesertaTrip;
use App\Models\Reservasi;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ReservasiController extends Controller
{
    private const SESSION_KEY = 'reservasi_open_trip';

    public function create(PaketTrip $paketTrip)
    {
        $paket = $paketTrip->load(['jadwals' => function ($query) {
                $query->where('status', 'open')
                    ->orderBy('tanggal_berangkat');
            }]);

        $sessionData = session(self::SESSION_KEY, []);

        return view('wisatawan.reservasi.create', [
            'paket' => $paket,
            'jadwals' => $paket->jadwals,
            'sessionData' => $sessionData,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paket_id' => ['required', 'integer', Rule::exists('paket_trips', 'paketId')],
            'jadwal_id' => ['required', 'integer', Rule::exists('jadwals', 'jadwalId')],
            'jml_peserta' => ['required', 'integer', 'min:1', 'max:20'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $paket = PaketTrip::findOrFail($validated['paket_id']);
        $jadwal = Jadwal::findOrFail($validated['jadwal_id']);

        if ((int) $jadwal->paketId !== (int) $paket->paketId) {
            return back()->withInput()->withErrors([
                'jadwal_id' => 'Jadwal yang dipilih tidak sesuai dengan paket trip ini.',
            ]);
        }

        if ($jadwal->status !== 'open') {
            return back()->withInput()->withErrors([
                'jadwal_id' => 'Jadwal ini sudah tidak tersedia.',
            ]);
        }

        $sisaKuota = max(0, (int) $jadwal->kuota_max - (int) $jadwal->kuota_terisi);

        if ((int) $validated['jml_peserta'] > $sisaKuota) {
            return back()->withInput()->withErrors([
                'jml_peserta' => 'Jumlah peserta melebihi sisa kuota yang tersedia (' . $sisaKuota . ').',
            ]);
        }

        $hargaPerPax = $jadwal->harga_override ?? $paket->harga;

        session()->put(self::SESSION_KEY, [
            'paket_id' => $paket->paketId,
            'paket_nama' => $paket->nama,
            'jadwal_id' => $jadwal->jadwalId,
            'jadwal_tanggal' => $jadwal->tanggal_berangkat,
            'jml_peserta' => (int) $validated['jml_peserta'],
            'catatan' => $validated['catatan'] ?? null,
            'harga_per_pax' => (float) $hargaPerPax,
            'total_harga' => (float) $hargaPerPax * (int) $validated['jml_peserta'],
            'sisa_kuota' => $sisaKuota,
        ]);

        return redirect()->route('reservasi.peserta');
    }

    public function createPeserta()
    {
        $flow = session(self::SESSION_KEY);

        if (! $flow) {
            return redirect()->route('paket-trip.index')->withErrors([
                'reservasi' => 'Silakan mulai reservasi dari halaman paket trip terlebih dahulu.',
            ]);
        }

        $paket = PaketTrip::findOrFail($flow['paket_id']);
        $jadwal = Jadwal::findOrFail($flow['jadwal_id']);

        return view('wisatawan.reservasi.peserta', compact('flow', 'paket', 'jadwal'));
    }

    public function storePeserta(Request $request)
    {
        $flow = session(self::SESSION_KEY);

        if (! $flow) {
            return redirect()->route('paket-trip.index')->withErrors([
                'reservasi' => 'Sesi reservasi sudah berakhir. Silakan ulangi dari awal.',
            ]);
        }

        $validated = $request->validate([
            'jml_peserta' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $jumlahPeserta = (int) $validated['jml_peserta'];

        if (isset($flow['sisa_kuota']) && $jumlahPeserta > (int) $flow['sisa_kuota']) {
            return back()->withInput()->withErrors([
                'jml_peserta' => 'Jumlah peserta melebihi sisa kuota yang tersedia (' . (int) $flow['sisa_kuota'] . ').',
            ]);
        }

        $rules = [];

        for ($index = 0; $index < $jumlahPeserta; $index++) {
            $rules['peserta.' . $index . '.nama'] = ['required', 'string', 'max:255'];
            $rules['peserta.' . $index . '.jenis_identitas'] = ['required', Rule::in(['ktp', 'paspor', 'sim'])];
            $rules['peserta.' . $index . '.no_identitas'] = ['required', 'string', 'max:30'];
            $rules['peserta.' . $index . '.jenis_kelamin'] = ['required', Rule::in(['laki-laki', 'perempuan'])];
            $rules['peserta.' . $index . '.tanggal_lahir'] = ['required', 'date'];
            $rules['peserta.' . $index . '.no_hp'] = ['nullable', 'string', 'max:20'];
        }

        $validatedPeserta = $request->validate($rules);

        $flow['jml_peserta'] = $jumlahPeserta;
        $flow['total_harga'] = (float) ($flow['harga_per_pax'] ?? 0) * $jumlahPeserta;
        $flow['data_peserta'] = array_slice($validatedPeserta['peserta'] ?? [], 0, $jumlahPeserta);
        session()->put(self::SESSION_KEY, $flow);

        return redirect()->route('reservasi.ringkasan');
    }

    public function ringkasan()
    {
        $flow = session(self::SESSION_KEY);

        if (! $flow) {
            return redirect()->route('paket-trip.index')->withErrors([
                'reservasi' => 'Silakan mulai reservasi dari halaman paket trip terlebih dahulu.',
            ]);
        }

        $paket = PaketTrip::findOrFail($flow['paket_id']);
        $jadwal = Jadwal::findOrFail($flow['jadwal_id']);

        return view('wisatawan.reservasi.ringkasan', compact('flow', 'paket', 'jadwal'));
    }

    public function checkout(Request $request, MidtransService $midtransService)
    {
        $flow = session(self::SESSION_KEY);

        if (! $flow) {
            return redirect()->route('paket-trip.index')->withErrors([
                'reservasi' => 'Sesi reservasi tidak ditemukan. Silakan ulangi proses reservasi.',
            ]);
        }

        if (empty($flow['data_peserta'])) {
            return redirect()->route('reservasi.peserta');
        }

        try {
            $reservasi = DB::transaction(function () use ($flow, $midtransService) {
                $jadwal = Jadwal::whereKey($flow['jadwal_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $sisaKuota = (int) $jadwal->kuota_max - (int) $jadwal->kuota_terisi;

                if ((int) $flow['jml_peserta'] > $sisaKuota) {
                    throw new \RuntimeException('Kuota jadwal tidak mencukupi lagi. Silakan pilih jadwal lain.');
                }

                $kodeReservasi = 'OT-' . now()->format('ymd') . '-' . strtoupper(Str::random(6));

                $reservasi = Reservasi::create([
                    'userId' => Auth::id(),
                    'jadwalId' => $jadwal->jadwalId,
                    'kode_reservasi' => $kodeReservasi,
                    'catatan' => $flow['catatan'] ?? null,
                    'jml_peserta' => (int) $flow['jml_peserta'],
                    'total_harga' => (float) $flow['total_harga'],
                    'status' => 'unpaid',
                ]);

                foreach ($flow['data_peserta'] as $peserta) {
                    PesertaTrip::create([
                        'reservasiId' => $reservasi->reservasiId,
                        'nama' => $peserta['nama'],
                        'jenis_identitas' => $peserta['jenis_identitas'],
                        'no_identitas' => $peserta['no_identitas'],
                        'jenis_kelamin' => $peserta['jenis_kelamin'],
                        'tanggal_lahir' => $peserta['tanggal_lahir'],
                        'no_hp' => $peserta['no_hp'] ?? null,
                    ]);
                }

                $jadwal->update([
                    'kuota_terisi' => $jadwal->kuota_terisi + (int) $flow['jml_peserta'],
                    'status' => ($jadwal->kuota_terisi + (int) $flow['jml_peserta']) >= (int) $jadwal->kuota_max ? 'full' : $jadwal->status,
                ]);

                $midtransService->syncPayment($reservasi);

                return $reservasi;
            });

            session()->forget(self::SESSION_KEY);

            return redirect()->route('reservasi.pembayaran', $reservasi->kode_reservasi);
        } catch (\Throwable $throwable) {
            return redirect()->route('reservasi.ringkasan')->withErrors([
                'checkout' => $throwable->getMessage(),
            ]);
        }
    }

    public function riwayat()
    {
        $reservasis = Reservasi::with(['jadwal.paketTrip', 'pembayaran'])
            ->where('userId', Auth::id())
            ->latest('reservasiId')
            ->get();

        return view('wisatawan.reservasi.riwayat', compact('reservasis'));
    }
}
