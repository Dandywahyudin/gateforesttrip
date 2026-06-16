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
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class JadwalController extends BaseController
{
    private const STATUS_OPTIONS = ['open', 'full', 'cancelled'];

    public function index(Request $request): View
    {
        return $this->renderIndex($request);
    }

    public function create(Request $request): View
    {
        return $this->renderForm($request, new Jadwal());
    }

    public function store(Request $request): RedirectResponse
    {
        [$jadwal] = $this->persistJadwal($request);

        event(JadwalKuotaUpdated::fromJadwal($jadwal));

        return $this->redirectAfterMutation(null, 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Request $request, Jadwal $jadwal): View
    {
        return $this->renderForm($request, $jadwal);
    }

    public function update(Request $request, Jadwal $jadwal): RedirectResponse
    {
        [$jadwal] = $this->persistJadwal($request, null, $jadwal);

        event(JadwalKuotaUpdated::fromJadwal($jadwal));

        return $this->redirectAfterMutation(null, 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal): RedirectResponse
    {
        $snapshot = $jadwal->fresh(['paketTrip']);
        $jadwal->delete();

        event(JadwalKuotaUpdated::fromJadwal($snapshot, true));

        return $this->redirectAfterMutation(null, 'Jadwal berhasil dihapus.');
    }

    public function nestedIndex(Request $request, PaketTrip $paketTrip): View
    {
        return $this->renderIndex($request, $paketTrip);
    }

    public function nestedCreate(Request $request, PaketTrip $paketTrip): View
    {
        return $this->renderForm($request, new Jadwal(['paketId' => $paketTrip->paketId]), $paketTrip);
    }

    public function nestedStore(Request $request, PaketTrip $paketTrip): RedirectResponse
    {
        [$jadwal] = $this->persistJadwal($request, $paketTrip);

        event(JadwalKuotaUpdated::fromJadwal($jadwal));

        return $this->redirectAfterMutation($paketTrip, 'Jadwal berhasil ditambahkan.');
    }

    public function nestedEdit(Request $request, PaketTrip $paketTrip, Jadwal $jadwal): View
    {
        $this->ensureJadwalBelongsToPaket($paketTrip, $jadwal);

        return $this->renderForm($request, $jadwal, $paketTrip);
    }

    public function nestedUpdate(Request $request, PaketTrip $paketTrip, Jadwal $jadwal): RedirectResponse
    {
        [$jadwal] = $this->persistJadwal($request, $paketTrip, $jadwal);

        event(JadwalKuotaUpdated::fromJadwal($jadwal));

        return $this->redirectAfterMutation($paketTrip, 'Jadwal berhasil diperbarui.');
    }

    public function nestedDestroy(PaketTrip $paketTrip, Jadwal $jadwal): RedirectResponse
    {
        $this->ensureJadwalBelongsToPaket($paketTrip, $jadwal);

        $snapshot = $jadwal->fresh(['paketTrip']);
        $jadwal->delete();

        event(JadwalKuotaUpdated::fromJadwal($snapshot, true));

        return $this->redirectAfterMutation($paketTrip, 'Jadwal berhasil dihapus.');
    }

    private function renderIndex(Request $request, ?PaketTrip $paketTrip = null): View
    {
        $filters = [
            'paket_id' => $paketTrip ? null : $request->query('paket_id'),
            'status' => $request->query('status'),
            'tanggal_mulai' => $this->dateFilter($request, 'tanggal_mulai'),
            'tanggal_selesai' => $this->dateFilter($request, 'tanggal_selesai'),
        ];

        $query = Jadwal::with('paketTrip')
            ->when($paketTrip, fn ($query) => $query->where('paketId', $paketTrip->paketId))
            ->when(! $paketTrip && $filters['paket_id'], fn ($query) => $query->where('paketId', $filters['paket_id']))
            ->when(in_array($filters['status'], self::STATUS_OPTIONS, true), fn ($query) => $query->where('status', $filters['status']))
            ->when($filters['tanggal_mulai'], fn ($query) => $query->whereDate('tanggal_berangkat', '>=', $filters['tanggal_mulai']))
            ->when($filters['tanggal_selesai'], fn ($query) => $query->whereDate('tanggal_berangkat', '<=', $filters['tanggal_selesai']));

        $jadwals = $query
            ->orderByDesc('tanggal_berangkat')
            ->orderByDesc('jadwalId')
            ->paginate(10)
            ->withQueryString();

        return view('admin.jadwal.index', [
            'jadwals' => $jadwals,
            'paketTrip' => $paketTrip,
            'paketTrips' => $this->paketTripOptions(),
            'filters' => $filters,
            'statusOptions' => self::STATUS_OPTIONS,
            'isNested' => $paketTrip !== null,
        ]);
    }

    private function renderForm(Request $request, Jadwal $jadwal, ?PaketTrip $paketTrip = null): View
    {
        $isNested = $paketTrip !== null;
        $isEdit = $jadwal->exists;
        $selectedPaketId = old('paketId', $paketTrip?->paketId ?? $jadwal->paketId ?? $request->query('paket_id'));

        if (! $jadwal->exists && $selectedPaketId) {
            $jadwal->paketId = (int) $selectedPaketId;
        }

        $action = $this->formAction($jadwal, $paketTrip);
        $cancelUrl = $this->indexUrl($paketTrip);

        return view($isEdit ? 'admin.jadwal.edit' : 'admin.jadwal.create', [
            'action' => $action,
            'cancelUrl' => $cancelUrl,
            'jadwal' => $jadwal,
            'lockedPaketTrip' => $isNested,
            'method' => $isEdit ? 'PUT' : 'POST',
            'paketTrip' => $paketTrip ?? $jadwal->paketTrip,
            'paketTrips' => $this->paketTripOptions(),
            'submitLabel' => $isEdit ? 'Simpan Perubahan' : 'Simpan Jadwal',
        ]);
    }

    private function persistJadwal(Request $request, ?PaketTrip $paketTrip = null, ?Jadwal $jadwal = null): array
    {
        if ($paketTrip && $jadwal) {
            $this->ensureJadwalBelongsToPaket($paketTrip, $jadwal);
        }

        [$paketTrip, $validated] = $this->validateData($request, $paketTrip, $jadwal);
        $kuotaTerisi = (int) ($jadwal?->kuota_terisi ?? 0);

        $payload = [
            'paketId' => $paketTrip->paketId,
            'tanggal_berangkat' => $validated['tanggal_berangkat'],
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'kuota_max' => $validated['kuota_max'],
            'kuota_terisi' => $kuotaTerisi,
            'status' => $kuotaTerisi >= $validated['kuota_max']
                ? 'full'
                : $validated['status'],
            'cutoff_booking' => $validated['cutoff_booking'],
        ];

        if ($jadwal) {
            $jadwal->update($payload);
        } else {
            $jadwal = Jadwal::create($payload);
        }

        return [$jadwal->fresh(['paketTrip']), $paketTrip];
    }

    private function validateData(Request $request, ?PaketTrip $paketTrip = null, ?Jadwal $jadwal = null): array
    {
        $rules = [
            'tanggal_berangkat' => ['required', 'date'],
            'tanggal_kembali' => ['required', 'date', 'after_or_equal:tanggal_berangkat'],
            'kuota_max' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(self::STATUS_OPTIONS)],
            'cutoff_booking' => ['nullable', 'date'],
        ];

        if (! $paketTrip) {
            $rules['paketId'] = ['required', 'exists:paket_trips,paketId'];
        }

        $validated = $request->validate($rules);
        $paketTrip ??= PaketTrip::whereKey($validated['paketId'])->firstOrFail();
        $kuotaTerisi = (int) ($jadwal?->kuota_terisi ?? 0);

        if ($kuotaTerisi > $validated['kuota_max']) {
            throw ValidationException::withMessages([
                'kuota_max' => 'Kuota maksimal tidak boleh lebih kecil dari kuota yang sudah terisi (' . $kuotaTerisi . ').',
            ]);
        }

        $tanggalBerangkat = Carbon::parse($validated['tanggal_berangkat']);
        $tanggalKembali = Carbon::parse($validated['tanggal_kembali']);
        $cutoffBooking = ! empty($validated['cutoff_booking'])
            ? Carbon::parse($validated['cutoff_booking'])
            : null;

        if ($cutoffBooking && $cutoffBooking->gt($tanggalBerangkat->copy()->endOfDay())) {
            throw ValidationException::withMessages([
                'cutoff_booking' => 'Cutoff booking tidak boleh lebih dari tanggal berangkat.',
            ]);
        }

        return [$paketTrip, [
            'tanggal_berangkat' => $tanggalBerangkat->toDateString(),
            'tanggal_kembali' => $tanggalKembali->toDateString(),
            'kuota_max' => (int) $validated['kuota_max'],
            'status' => $validated['status'],
            'cutoff_booking' => $cutoffBooking?->format('Y-m-d H:i:s'),
        ]];
    }

    private function paketTripOptions()
    {
        return PaketTrip::orderBy('nama')->get();
    }

    private function formAction(Jadwal $jadwal, ?PaketTrip $paketTrip = null): string
    {
        if ($jadwal->exists) {
            return $paketTrip
                ? route('admin.paket-trip.jadwal.update', [$paketTrip, $jadwal])
                : route('admin.jadwal.update', $jadwal);
        }

        return $paketTrip
            ? route('admin.paket-trip.jadwal.store', $paketTrip)
            : route('admin.jadwal.store');
    }

    private function indexUrl(?PaketTrip $paketTrip = null): string
    {
        return $paketTrip
            ? route('admin.paket-trip.jadwal.index', $paketTrip)
            : route('admin.jadwal.index');
    }

    private function redirectAfterMutation(?PaketTrip $paketTrip, string $message): RedirectResponse
    {
        return redirect($this->indexUrl($paketTrip))->with('success', $message);
    }

    private function dateFilter(Request $request, string $key): ?string
    {
        if (! $request->filled($key)) {
            return null;
        }

        try {
            return Carbon::parse($request->query($key))->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    private function ensureJadwalBelongsToPaket(PaketTrip $paketTrip, Jadwal $jadwal): void
    {
        abort_unless((int) $jadwal->paketId === (int) $paketTrip->paketId, 404);
    }
}
