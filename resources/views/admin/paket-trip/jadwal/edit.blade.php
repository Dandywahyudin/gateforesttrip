@extends('layouts.admin')

@section('title', 'Edit Jadwal')
@section('page-title', 'Edit Jadwal')

@section('content')
    <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.28em] text-primary">Edit Jadwal</p>
                <h3 class="mt-2 text-3xl font-display font-black uppercase text-gray-900">{{ $paketTrip->nama }}</h3>
                <p class="mt-2 text-sm text-gray-500">{{ \Illuminate\Support\Carbon::parse($jadwal->tanggal_berangkat)->translatedFormat('d M Y') }}</p>
            </div>
            <a href="{{ route('admin.paket-trip.jadwal.index', $paketTrip) }}" class="rounded-full border border-gray-200 px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-gray-700 transition hover:border-primary hover:text-primary">Kembali</a>
        </div>

        @include('admin.paket-trip.jadwal._form', [
            'paketTrip' => $paketTrip,
            'jadwal' => $jadwal,
            'action' => route('admin.paket-trip.jadwal.update', [$paketTrip, $jadwal->jadwalId]),
            'method' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
        ])
    </div>
@endsection