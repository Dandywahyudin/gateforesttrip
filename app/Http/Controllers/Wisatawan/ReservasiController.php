<?php

namespace App\Http\Controllers\Wisatawan;

use App\Models\Jadwal;
use App\Models\PaketTrip;
use App\Models\Reservasi;
use App\Models\PesertaTrip;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    /**
     * Show reservasi creation form
     */
    public function create(Request $request)
    {
        $paketId = $request->query('paketId');
        $jml_peserta = $request->query('jml_peserta', 1);
        
        $paket = PaketTrip::findOrFail($paketId);
        
        // Get available jadwals for this paket
        $jadwals = Jadwal::where('paketId', $paketId)
            ->where('status', 'open')
            ->where('cutoff_booking', '>', now())
            ->orderBy('tanggal_berangkat', 'asc')
            ->get();
        
        return view('wisatawan.reservasi.create', compact('paket', 'jadwals', 'jml_peserta'));
    }

    /**
     * Store reservation to database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'paketId' => 'required|exists:paket_trips,paketId',
            'jadwalId' => 'required|exists:jadwals,jadwalId',
            'jml_peserta' => 'required|integer|min:1|max:30',
            'catatan' => 'nullable|string',
            'peserta.*.nama' => 'required|string',
            'peserta.*.jenis_identitas' => 'required|in:ktp,paspor,sim',
            'peserta.*.no_identitas' => 'required|string',
            'peserta.*.jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'peserta.*.tanggal_lahir' => 'required|date',
            'peserta.*.email' => 'email|nullable',
            'peserta.*.no_hp' => 'required|string',
        ]);

        $jadwal = Jadwal::findOrFail($validated['jadwalId']);
        $paket = PaketTrip::findOrFail($validated['paketId']);
        
        // Check kuota
        if ($jadwal->kuota_terisi + $validated['jml_peserta'] > $jadwal->kuota_max) {
            return back()->withErrors(['kuota' => 'Kuota tidak tersedia untuk jumlah peserta ini']);
        }

        // Calculate total price
        $harga_per_orang = $jadwal->harga_override ?? $paket->harga;
        $total_harga = $harga_per_orang * $validated['jml_peserta'];

        // Create reservation
        $reservasi = Reservasi::create([
            'kode_reservasi' => 'RES-' . strtoupper(Str::random(8)),
            'userId' => auth()->id(),
            'jadwalId' => $validated['jadwalId'],
            'jml_peserta' => $validated['jml_peserta'],
            'total_harga' => $total_harga,
            'catatan' => $validated['catatan'] ?? null,
            'status' => 'unpaid',
        ]);

        // Create peserta entries
        if (isset($validated['peserta'])) {
            foreach ($validated['peserta'] as $peserta) {
                PesertaTrip::create([
                    'reservasiId' => $reservasi->reservasiId,
                    'nama' => $peserta['nama'],
                    'jenis_identitas' => $peserta['jenis_identitas'],
                    'no_identitas' => $peserta['no_identitas'],
                    'jenis_kelamin' => $peserta['jenis_kelamin'],
                    'tanggal_lahir' => $peserta['tanggal_lahir'],
                    'no_hp' => $peserta['no_hp'],
                ]);
            }
        }

        // Update jadwal kuota
        $jadwal->increment('kuota_terisi', $validated['jml_peserta']);

        return redirect()->route('reservasi.show', $reservasi->reservasiId)
            ->with('success', 'Reservasi berhasil dibuat! Silahkan lanjutkan dengan pembayaran.');
    }

    /**
     * Show reservation confirmation
     */
    public function show($id)
    {
        $reservasi = Reservasi::with(['user', 'jadwal.paketTrip', 'peserta'])
            ->findOrFail($id);

        // Check authorization
        if ($reservasi->userId !== auth()->id() && !auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        return view('wisatawan.reservasi.show', compact('reservasi'));
    }

    /**
     * List user reservations
     */
    public function index()
    {
        $reservasis = Reservasi::where('userId', auth()->id())
            ->with(['jadwal.paketTrip', 'pembayaran'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('wisatawan.reservasi.index', compact('reservasis'));
    }

    public function edit($id)
    {
        // Implementation for showing reservation edit form
    }

    public function update(Request $request, $id)
    {
        // Implementation for updating reservation
    }

    public function destroy($id)
    {
        // Implementation for deleting reservation
    }
}
