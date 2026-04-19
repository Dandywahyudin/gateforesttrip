@extends('layouts.app')

@section('title', 'Riwayat Reservasi')

@section('content')
<section class="w-full pt-32 pb-16 bg-[radial-gradient(circle_at_top_right,_rgba(21,128,61,0.12),_transparent_30%),linear-gradient(to_bottom,_#f8faf7,_#ffffff)]">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16">
        <div class="mb-8">
            <x-breadcrumbs :items="[
                ['label' => 'Home', 'url' => url('/')],
                ['label' => 'Trip', 'url' => route('paket-trip.index')],
                ['label' => 'Riwayat', 'url' => null],
            ]" />
            <h1 class="mt-2 text-4xl md:text-5xl font-display font-black uppercase text-forest-green">Riwayat Reservasi</h1>
            <p class="mt-3 text-forest-green/70 max-w-2xl">Lihat seluruh reservasi open trip Anda, termasuk status pembayaran dan akses cepat ke detail perjalanan.</p>
        </div>

        @if($reservasis->isEmpty())
            <div class="rounded-[28px] border border-dashed border-forest-green/20 bg-white/90 p-8 text-center shadow-sm">
                <h2 class="text-2xl font-display font-black uppercase text-forest-green">Belum ada riwayat reservasi</h2>
                <p class="mt-3 text-sm text-forest-green/70">Saat Anda melakukan reservasi pertama, data perjalanan akan muncul di halaman ini.</p>
                <a href="{{ route('paket-trip.index') }}" class="mt-6 inline-flex items-center justify-center rounded-2xl bg-forest-green px-5 py-4 text-sm font-black uppercase tracking-[0.25em] text-white transition hover:-translate-y-0.5 hover:bg-forest-green/90">Lihat Paket Trip</a>
            </div>
        @else
            <div class="grid gap-6">
                @foreach($reservasis as $reservasi)
                    @php
                        $isPaid = in_array(optional($reservasi->pembayaran)->status, ['settlement', 'capture'], true) || $reservasi->status === 'paid';
                        $paymentStatus = $reservasi->pembayaran?->status ?? 'pending';
                    @endphp
                    <article class="rounded-[28px] border border-white/60 bg-white/90 p-6 shadow-[0_18px_60px_rgba(15,23,42,0.08)] backdrop-blur-md">
                        <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-6">
                            <div class="space-y-4">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.28em] text-primary">{{ $reservasi->kode_reservasi }}</p>
                                    <h2 class="mt-2 text-2xl font-display font-black uppercase text-forest-green">{{ $reservasi->jadwal?->paketTrip?->nama }}</h2>
                                    <p class="mt-1 text-sm text-forest-green/70">{{ $reservasi->jadwal?->paketTrip?->lokasi }}</p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                                    <div class="rounded-2xl bg-background-light p-4">
                                        <p class="text-[10px] font-black uppercase tracking-[0.25em] text-forest-green/40 mb-1">Jadwal</p>
                                        <p class="font-black text-forest-green">{{ \Illuminate\Support\Carbon::parse($reservasi->jadwal?->tanggal_berangkat)->translatedFormat('d M Y') }}</p>
                                    </div>
                                    <div class="rounded-2xl bg-background-light p-4">
                                        <p class="text-[10px] font-black uppercase tracking-[0.25em] text-forest-green/40 mb-1">Peserta</p>
                                        <p class="font-black text-forest-green">{{ $reservasi->jml_peserta }} orang</p>
                                    </div>
                                    <div class="rounded-2xl bg-background-light p-4">
                                        <p class="text-[10px] font-black uppercase tracking-[0.25em] text-forest-green/40 mb-1">Total</p>
                                        <p class="font-black text-forest-green">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col items-start xl:items-end gap-3">
                                <span class="px-4 py-2 rounded-full text-xs font-black uppercase tracking-[0.25em] {{ $isPaid ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    Reservasi: {{ $reservasi->status }}
                                </span>
                                <span class="px-4 py-2 rounded-full text-xs font-black uppercase tracking-[0.25em] {{ $isPaid ? 'bg-green-100 text-green-700' : 'bg-primary/10 text-primary' }}">
                                    Pembayaran: {{ $paymentStatus }}
                                </span>
                                <p class="text-xs uppercase tracking-[0.25em] text-forest-green/40">Order: {{ $reservasi->pembayaran?->orderId ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('reservasi.status', $reservasi->kode_reservasi) }}" class="inline-flex flex-1 items-center justify-center rounded-2xl border border-forest-green/10 px-5 py-4 text-sm font-black uppercase tracking-[0.25em] text-forest-green transition hover:border-primary/40">Lihat Status</a>
                            @if(! $isPaid)
                                <a href="{{ route('reservasi.pembayaran', $reservasi->kode_reservasi) }}" class="inline-flex flex-1 items-center justify-center rounded-2xl bg-forest-green px-5 py-4 text-sm font-black uppercase tracking-[0.25em] text-white transition hover:-translate-y-0.5 hover:bg-forest-green/90">Lanjut Bayar</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection