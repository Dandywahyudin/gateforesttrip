<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use App\Models\PaketTrip;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaketTripController extends BaseController
{
    public function index(): View
    {
        $paketTrips = PaketTrip::withCount([
                'jadwals as jadwal_open_count' => function ($query) {
                    $query->where('status', 'open');
                },
                'reservasis as reservasi_count',
            ])
            ->latest('paketId')
            ->paginate(5);

        return view('admin.paket-trip.index', compact('paketTrips'));
    }

    public function create(): View
    {
        return view('admin.paket-trip.create', [
            'paketTrip' => new PaketTrip(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);
        $images = $this->syncImages($request);

        PaketTrip::create([
            ...$validated,
            ...$images,
            'slug' => $this->generateUniqueSlug($validated['nama']),
        ]);

        return redirect()->route('admin.paket-trip.index')->with('success', 'Paket trip berhasil ditambahkan.');
    }

    public function edit(PaketTrip $paketTrip): View
    {
        return view('admin.paket-trip.edit', compact('paketTrip'));
    }

    public function update(Request $request, PaketTrip $paketTrip): RedirectResponse
    {
        $validated = $this->validateData($request, $paketTrip->paketId);
        $images = $this->syncImages($request, $paketTrip);

        $paketTrip->update([
            ...$validated,
            ...$images,
            'slug' => $this->generateUniqueSlug($validated['nama'], $paketTrip->paketId),
        ]);

        return redirect()->route('admin.paket-trip.index')->with('success', 'Paket trip berhasil diperbarui.');
    }

    public function destroy(PaketTrip $paketTrip): RedirectResponse
    {
        $this->deleteStoredImages($paketTrip);
        $paketTrip->delete();

        return redirect()->route('admin.paket-trip.index')->with('success', 'Paket trip berhasil dihapus.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return array_merge($request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'fasilitas' => ['required', 'string'],
            'lokasi' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'meeting_point' => ['nullable', 'string'],
            'include' => ['nullable', 'string'],
            'exclude' => ['nullable', 'string'],
            'durasi_hari' => ['required', 'integer', 'min:1'],
            'harga' => ['required', 'numeric', 'min:0'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'foto2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'foto3' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'foto4' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hapus_foto' => ['nullable', 'boolean'],
            'hapus_foto2' => ['nullable', 'boolean'],
            'hapus_foto3' => ['nullable', 'boolean'],
            'hapus_foto4' => ['nullable', 'boolean'],
            'aktif' => ['nullable', 'boolean'],
        ]), [
            'aktif' => $request->boolean('aktif'),
        ]);
    }

    private function syncImages(Request $request, ?PaketTrip $paketTrip = null): array
    {
        $images = [];

        foreach (['foto', 'foto2', 'foto3', 'foto4'] as $field) {
            if ($request->hasFile($field)) {
                if ($paketTrip?->{$field}) {
                    $this->deleteStoredImage($paketTrip->{$field});
                }

                $images[$field] = $request->file($field)->store('paket-trip', 'public');
                continue;
            }

            if ($request->boolean('hapus_' . $field) && $paketTrip?->{$field}) {
                $this->deleteStoredImage($paketTrip->{$field});
                $images[$field] = null;
            }
        }

        return $images;
    }

    private function deleteStoredImages(PaketTrip $paketTrip): void
    {
        foreach (['foto', 'foto2', 'foto3', 'foto4'] as $field) {
            $this->deleteStoredImage($paketTrip->{$field});
        }
    }

    private function deleteStoredImage(?string $path): void
    {
        if (! $path || filter_var($path, FILTER_VALIDATE_URL)) {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 2;

        while (
            PaketTrip::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('paketId', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}