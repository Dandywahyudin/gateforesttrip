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
use Illuminate\Support\Carbon;
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

        $jadwals = $paket->jadwals->map(function (Jadwal $jadwal) {
            $jadwal->setAttribute('sisa_kuota_tersedia', $this->getSisaKuotaTersedia($jadwal));

            return $jadwal;
        });

        $sessionData = session(self::SESSION_KEY, []);

        return view('wisatawan.reservasi.create', [
            'paket' => $paket,
            'jadwals' => $jadwals,
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
        ], [
            'paket_id.required' => 'ID paket trip diperlukan.',
            'paket_id.exists' => 'Paket trip tidak ditemukan.',
            'jadwal_id.required' => 'Silakan pilih jadwal keberangkatan terlebih dahulu.',
            'jadwal_id.exists' => 'Jadwal yang dipilih tidak ditemukan.',
            'jml_peserta.required' => 'Jumlah peserta diperlukan.',
            'jml_peserta.min' => 'Jumlah peserta minimal 1 orang.',
            'jml_peserta.max' => 'Jumlah peserta maksimal 20 orang.',
            'catatan.max' => 'Catatan tidak boleh lebih dari 1000 karakter.',
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

        $sisaKuota = $this->getSisaKuotaTersedia($jadwal);

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
        ], [
            'jml_peserta.required' => 'Jumlah peserta diperlukan.',
            'jml_peserta.integer' => 'Jumlah peserta harus berupa angka.',
            'jml_peserta.min' => 'Jumlah peserta minimal 1 orang.',
            'jml_peserta.max' => 'Jumlah peserta maksimal 20 orang.',
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
            $rules['peserta.' . $index . '.email'] = ['required', 'email', 'max:255'];
            $rules['peserta.' . $index . '.jenis_kelamin'] = ['required', Rule::in(['laki-laki', 'perempuan'])];
            $rules['peserta.' . $index . '.tanggal_lahir'] = ['required', 'date'];
            $rules['peserta.' . $index . '.no_hp'] = ['nullable', 'string', 'max:20'];
        }

        $messages = [];
        for ($index = 0; $index < $jumlahPeserta; $index++) {
            $messages['peserta.' . $index . '.nama.required'] = 'Nama peserta ' . ($index + 1) . ' diperlukan.';
            $messages['peserta.' . $index . '.nama.max'] = 'Nama peserta ' . ($index + 1) . ' tidak boleh lebih dari 255 karakter.';
            $messages['peserta.' . $index . '.email.required'] = 'Email peserta ' . ($index + 1) . ' diperlukan.';
            $messages['peserta.' . $index . '.email.email'] = 'Format email peserta ' . ($index + 1) . ' tidak valid.';
            $messages['peserta.' . $index . '.jenis_kelamin.required'] = 'Jenis kelamin peserta ' . ($index + 1) . ' diperlukan.';
            $messages['peserta.' . $index . '.jenis_kelamin.in'] = 'Jenis kelamin peserta ' . ($index + 1) . ' harus laki-laki atau perempuan.';
            $messages['peserta.' . $index . '.tanggal_lahir.required'] = 'Tanggal lahir peserta ' . ($index + 1) . ' diperlukan.';
            $messages['peserta.' . $index . '.tanggal_lahir.date'] = 'Tanggal lahir peserta ' . ($index + 1) . ' tidak valid.';
            $messages['peserta.' . $index . '.no_hp.max'] = 'Nomor HP peserta ' . ($index + 1) . ' tidak boleh lebih dari 20 karakter.';
        }

        $validatedPeserta = $request->validate($rules, $messages);

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
                    ->with('paketTrip')
                    ->lockForUpdate()
                    ->firstOrFail();
                $paket = $jadwal->paketTrip ?? PaketTrip::whereKey($jadwal->paketId)->firstOrFail();

                $sisaKuota = $this->getSisaKuotaTersedia($jadwal);

                if ((int) $flow['jml_peserta'] > $sisaKuota) {
                    throw new \RuntimeException('Kuota jadwal tidak mencukupi lagi. Silakan pilih jadwal lain.');
                }

                $kodeReservasi = $this->generateReservasiCode($paket, $jadwal);

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
                        'email' => $peserta['email'],
                        'jenis_kelamin' => $peserta['jenis_kelamin'],
                        'tanggal_lahir' => $peserta['tanggal_lahir'],
                        'no_hp' => $peserta['no_hp'] ?? null,
                    ]);
                }

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

        $this->syncExpiredPayments($reservasis);

        $reservasis->load(['jadwal.paketTrip', 'pembayaran']);

        return view('wisatawan.reservasi.riwayat', compact('reservasis'));
    }

    private function getSisaKuotaTersedia(Jadwal $jadwal): int
    {
        return max(0, (int) $jadwal->kuota_max - (int) $jadwal->kuota_terisi);
    }

    private function generateReservasiCode(PaketTrip $paket, Jadwal $jadwal): string
{
    $prefix = 'OT';
    $tanggal = Carbon::parse($jadwal->tanggal_berangkat)->format('Ymd'); 

    // Ambil reservasi terakhir di tanggal yang sama
    $lastReservasi = Reservasi::where('kode_reservasi', 'like', "{$prefix}-{$tanggal}-%")
        ->orderBy('kode_reservasi', 'desc')
        ->lockForUpdate()
        ->first();

    if ($lastReservasi) {
        // Ambil angka terakhir (0001)
        $lastNumber = (int) substr($lastReservasi->kode_reservasi, -4);
        $nextNumber = $lastNumber + 1;
    } else {
        $nextNumber = 1;
    }

    // Format jadi 4 digit
    $urutan = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

    // Generate kode
    $kodeReservasi = "{$prefix}-{$tanggal}-{$urutan}";

    return $kodeReservasi;
}

    private function buildPaketCode(PaketTrip $paket): string
    {
        $words = preg_split('/[^A-Za-z0-9]+/', (string) $paket->nama, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $code = '';

        foreach ($words as $word) {
            $code .= strtoupper(Str::substr($word, 0, 1));

            if (strlen($code) === 3) {
                break;
            }
        }

        if ($code === '') {
            $fallback = preg_replace('/[^A-Za-z0-9]+/', '', (string) ($paket->slug ?: $paket->nama));
            $code = strtoupper(Str::substr($fallback, 0, 3));
        }

        return str_pad(substr($code, 0, 3), 3, 'X');
    }

    private function syncExpiredPayments($reservasis): void
    {
        $now = now('Asia/Jakarta');

        foreach ($reservasis as $reservasi) {
            $pembayaran = $reservasi->pembayaran;

            if (! $pembayaran || ! in_array($pembayaran->status, ['pending'], true) || ! $pembayaran->expired_at) {
                continue;
            }

            $expiredAt = $pembayaran->expired_at->timezone('Asia/Jakarta');

            if ($now->lt($expiredAt)) {
                continue;
            }

            DB::transaction(function () use ($reservasi) {
                $pembayaran = $reservasi->pembayaran()->lockForUpdate()->first();

                if (! $pembayaran || ! in_array($pembayaran->status, ['pending'], true)) {
                    return;
                }

                $pembayaran->update([
                    'status' => 'expire',
                ]);

                $reservasi = Reservasi::whereKey($reservasi->reservasiId)
                    ->lockForUpdate()
                    ->first();

                if ($reservasi && $reservasi->status !== 'paid') {
                    $reservasi->update([
                        'status' => 'cancelled',
                    ]);
                }
            });
        }
    }
}
