@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-400">Total Paket</p>
                <p class="mt-3 text-3xl font-black text-gray-900">{{ $stats['total_paket'] }}</p>
            </div>
            <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-400">Paket Aktif</p>
                <p class="mt-3 text-3xl font-black text-gray-900">{{ $stats['paket_aktif'] }}</p>
            </div>
            <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-400">Total Jadwal</p>
                <p class="mt-3 text-3xl font-black text-gray-900">{{ $stats['total_jadwal'] }}</p>
            </div>
            <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-400">Reservasi</p>
                <p class="mt-3 text-3xl font-black text-gray-900">{{ $stats['total_reservasi'] }}</p>
            </div>

            <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-400">Omzet</p>
                <p class="mt-3 text-lg font-black text-gray-900">Rp {{ number_format($stats['omzet'], 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.55fr)_minmax(320px,0.9fr)] xl:items-start">
            <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div class="min-w-0">
                        <p class="text-[10px] font-black uppercase tracking-[0.28em] text-primary">Kelola Paket Trip</p>
                        <h3 class="mt-2 text-2xl font-display font-black uppercase text-gray-900">Pusat Paket Trip</h3>
                        <p class="mt-2 text-sm text-gray-500">Daftar paket trip yang aktif dan jadwal terbuka untuk dipantau admin.</p>
                    </div>
                    <a href="{{ route('admin.paket-trip.index') }}" class="inline-flex shrink-0 items-center justify-center rounded-full bg-primary px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-white transition hover:bg-primary-dark">Buka</a>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2 2xl:grid-cols-3">
                    @foreach($paketTrips as $paket)
                        <article class="flex h-full flex-col rounded-lg border border-gray-100 bg-gray-50 p-5 transition hover:border-primary/20 hover:bg-white">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-[10px] font-black uppercase tracking-[0.25em] text-primary">{{ $paket->slug }}</p>
                                    <h4 class="mt-2 truncate text-lg font-black uppercase text-gray-900">{{ $paket->nama }}</h4>
                                </div>
                                <span class="shrink-0 rounded-full {{ $paket->aktif ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }} px-3 py-1 text-[10px] font-black uppercase tracking-[0.22em]">
                                    {{ $paket->aktif ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>

                            <dl class="mt-5 space-y-3 text-sm text-gray-600">
                                <div class="flex items-center justify-between gap-4 rounded-lg bg-white px-4 py-3 shadow-sm">
                                    <dt>Harga</dt>
                                    <dd class="font-black text-gray-900">Rp {{ number_format($paket->harga, 0, ',', '.') }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-4 rounded-lg bg-white px-4 py-3 shadow-sm">
                                    <dt>Jadwal open</dt>
                                    <dd class="font-black text-gray-900">{{ $paket->jadwal_open_count }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-4 rounded-lg bg-white px-4 py-3 shadow-sm">
                                    <dt>Reservasi</dt>
                                    <dd class="font-black text-gray-900">{{ $paket->reservasi_count }}</dd>
                                </div>
                            </dl>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-1">
                <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-[10px] font-black uppercase tracking-[0.28em] text-primary">Kelola Jadwal</p>
                            <h3 class="mt-2 text-2xl font-display font-black uppercase text-gray-900">Jadwal trip aktif</h3>
                            <p class="mt-2 text-sm text-gray-500">Kelola seluruh jadwal dari satu halaman ringkasan.</p>
                        </div>
                        <a href="{{ route('admin.jadwal.index') }}" class="inline-flex shrink-0 items-center justify-center rounded-full border border-gray-200 px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-gray-700 transition hover:border-primary hover:text-primary">Kelola</a>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-[10px] font-black uppercase tracking-[0.28em] text-primary">Kelola Reservasi</p>
                            <h3 class="mt-2 text-2xl font-display font-black uppercase text-gray-900">Reservasi masuk</h3>
                            <p class="mt-2 text-sm text-gray-500">Pantau transaksi, peserta, dan status pembayaran.</p>
                        </div>
                        <a href="{{ route('admin.reservasi.index') }}" class="inline-flex shrink-0 items-center justify-center rounded-full border border-gray-200 px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-gray-700 transition hover:border-primary hover:text-primary">Kelola</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.28em] text-primary">Aktivitas Terbaru</p>
                    <h3 class="mt-2 text-2xl font-display font-black uppercase text-gray-900">Reservasi terakhir</h3>
                </div>
            </div>

            <div class="mt-5 space-y-3">
                @forelse($reservasis as $reservasi)
                    <div class="rounded-lg bg-gray-50 p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <p class="text-xs font-black uppercase tracking-[0.22em] text-primary">{{ $reservasi->kode_reservasi }}</p>
                                <h4 class="mt-1 text-sm font-black text-gray-900">{{ $reservasi->jadwal?->paketTrip?->nama }}</h4>
                                <p class="mt-1 text-xs text-gray-500">{{ $reservasi->user?->nama }}</p>
                            </div>
                            <span class="inline-flex shrink-0 rounded-full {{ $reservasi->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} px-3 py-1 text-[10px] font-black uppercase tracking-[0.22em]">
                                {{ $reservasi->status }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="rounded-lg border border-dashed border-gray-200 p-4 text-sm text-gray-500">Belum ada reservasi yang masuk.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection