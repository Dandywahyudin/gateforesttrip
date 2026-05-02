<?php

namespace App\Http\Controllers\Admin;

use App\Events\JadwalKuotaUpdated;
use App\Http\Controllers\Controller as BaseController;
use App\Models\Jadwal;
use App\Models\PaketTrip;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JadwalController extends BaseController
{
    public function index(PaketTrip $paketTrip): View
    {
        $jadwals = Jadwal::where('paketId', $paketTrip->paketId)
            ->orderBy('tanggal_berangkat')
            ->get();

        return view('admin.paket-trip.jadwal.index', compact('paketTrip', 'jadwals'));
    }

    public function create(PaketTrip $paketTrip): View
    {
        return view('admin.paket-trip.jadwal.create', [
            'paketTrip' => $paketTrip,
            'jadwal' => new Jadwal(),
        ]);
    }

    public function store(Request $request, PaketTrip $paketTrip): RedirectResponse
    {
        $validated = $this->validateData($request);
        $kuotaTerisi = 0;

        $jadwal = Jadwal::create([
            'paketId' => $paketTrip->paketId,
            'tanggal_berangkat' => $validated['tanggal_berangkat'],
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'kuota_max' => $validated['kuota_max'],
            'kuota_terisi' => $kuotaTerisi,
            'status' => $kuotaTerisi >= $validated['kuota_max']
                ? 'full'
                : $validated['status'],
            'harga_override' => $validated['harga_override'],
            'cutoff_booking' => $validated['cutoff_booking'],
        ]);

        event(JadwalKuotaUpdated::fromJadwal($jadwal->fresh(['paketTrip'])));

        return redirect()->route('admin.paket-trip.jadwal.index', $paketTrip)->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(PaketTrip $paketTrip, int $jadwalId): View
    {
        $jadwal = Jadwal::where('paketId', $paketTrip->paketId)->where('jadwalId', $jadwalId)->firstOrFail();

        return view('admin.paket-trip.jadwal.edit', compact('paketTrip', 'jadwal'));
    }

    public function update(Request $request, PaketTrip $paketTrip, int $jadwalId): RedirectResponse
    {
        $jadwal = Jadwal::where('paketId', $paketTrip->paketId)->where('jadwalId', $jadwalId)->firstOrFail();
        $validated = $this->validateData($request, $jadwal);
        $kuotaTerisi = (int) $jadwal->kuota_terisi;

        $jadwal->update([
            'tanggal_berangkat' => $validated['tanggal_berangkat'],
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'kuota_max' => $validated['kuota_max'],
            'kuota_terisi' => $kuotaTerisi,
            'status' => $kuotaTerisi >= $validated['kuota_max']
                ? 'full'
                : $validated['status'],
            'harga_override' => $validated['harga_override'],
            'cutoff_booking' => $validated['cutoff_booking'],
        ]);

        event(JadwalKuotaUpdated::fromJadwal($jadwal->fresh(['paketTrip'])));

        return redirect()->route('admin.paket-trip.jadwal.index', $paketTrip)->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(PaketTrip $paketTrip, int $jadwalId): RedirectResponse
    {
        $jadwal = Jadwal::where('paketId', $paketTrip->paketId)->where('jadwalId', $jadwalId)->firstOrFail();
        $snapshot = $jadwal->fresh(['paketTrip']);
        $jadwal->delete();

        event(JadwalKuotaUpdated::fromJadwal($snapshot, true));

        return redirect()->route('admin.paket-trip.jadwal.index', $paketTrip)->with('success', 'Jadwal berhasil dihapus.');
    }

    private function validateData(Request $request, ?Jadwal $jadwal = null): array
    {
        $validated = $request->validate([
            'tanggal_berangkat' => ['required', 'date'],
            'tanggal_kembali' => ['required', 'date', 'after_or_equal:tanggal_berangkat'],
            'kuota_max' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['open', 'full', 'cancelled'])],
            'harga_override' => ['nullable', 'numeric', 'min:0'],
            'cutoff_booking' => ['nullable', 'date'],
        ]);

        $kuotaTerisi = (int) ($jadwal?->kuota_terisi ?? 0);

        if ($kuotaTerisi > $validated['kuota_max']) {
            abort(422, 'Kuota terisi tidak boleh lebih besar dari kuota maksimal.');
        }

        return [
            'tanggal_berangkat' => Carbon::parse($validated['tanggal_berangkat'])->toDateString(),
            'tanggal_kembali' => Carbon::parse($validated['tanggal_kembali'])->toDateString(),
            'kuota_max' => (int) $validated['kuota_max'],
            'kuota_terisi' => $kuotaTerisi,
            'status' => $validated['status'],
            'harga_override' => $validated['harga_override'] ?: null,
            'cutoff_booking' => ! empty($validated['cutoff_booking']) ? Carbon::parse($validated['cutoff_booking']) : null,
        ];
    }
}