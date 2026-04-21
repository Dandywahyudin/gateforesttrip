@extends('layouts.admin')

@section('title', 'Tambah Paket Trip')
@section('page-title', 'Tambah Paket Trip')

@section('content')
    <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h3 class="mt-2 text-3xl font-display font-black uppercase text-gray-900">Tambah paket trip</h3>
            </div>
            <a href="{{ route('admin.paket-trip.index') }}" class="rounded-full border border-gray-200 px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-gray-700 transition hover:border-primary hover:text-primary">Kembali</a>
        </div>

        @include('admin.paket-trip._form', [
            'paketTrip' => $paketTrip,
            'action' => route('admin.paket-trip.store'),
            'method' => 'POST',
            'submitLabel' => 'Simpan Paket',
        ])
    </div>
@endsection