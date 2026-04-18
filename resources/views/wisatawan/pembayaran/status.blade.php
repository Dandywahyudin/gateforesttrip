@extends('layouts.app')

@section('title', 'Status - ' . $reservasi->kode_reservasi)

@section('content')
<section class="w-full pt-32 pb-16 bg-[radial-gradient(circle_at_top_right,_rgba(21,128,61,0.12),_transparent_30%),linear-gradient(to_bottom,_#f8faf7,_#ffffff)]">
    <div class="max-w-[1200px] mx-auto px-6 lg:px-16">
        <div class="mb-8">
            <x-breadcrumbs :items="[
                ['label' => 'Home', 'url' => url('/')],
                ['label' => 'Trip', 'url' => route('paket-trip.index')],
                ['label' => $reservasi->jadwal?->paketTrip?->nama, 'url' => $reservasi->jadwal?->paketTrip ? route('paket-trip.show', $reservasi->jadwal->paketTrip) : null],
                ['label' => 'Status', 'url' => null],
            ]" />
            <h1 class="mt-2 text-4xl md:text-5xl font-display font-black uppercase text-forest-green">Status Reservasi</h1>
            <p class="mt-3 text-forest-green/70 max-w-2xl">Pantau status reservasi dan pembayaran Anda di halaman ini.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-7 space-y-6">
                <div class="rounded-2xl border border-forest-green/10 bg-white p-6 shadow-sm">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.25em] text-forest-green/50">Kode Reservasi</p>
                            <h2 class="mt-2 text-2xl font-display font-black uppercase text-forest-green">{{ $reservasi->kode_reservasi }}</h2>
                            <p class="mt-1 text-sm text-forest-green/60">{{ $reservasi->jadwal?->paketTrip?->nama }}</p>
                        </div>
                        <div class="flex flex-col gap-2 items-start md:items-end">
                            <span class="px-4 py-2 rounded-full text-xs font-black uppercase tracking-[0.25em] {{ $reservasi->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                Reservasi: {{ $reservasi->status }}
                            </span>
                            <span class="px-4 py-2 rounded-full text-xs font-black uppercase tracking-[0.25em] {{ in_array($pembayaran->status, ['settlement', 'capture'], true) ? 'bg-green-100 text-green-700' : 'bg-primary/10 text-primary' }}">
                                Pembayaran: {{ $pembayaran->status }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="rounded-xl bg-background-light p-4"><p class="text-forest-green/50 text-xs font-black uppercase tracking-[0.25em] mb-2">Tanggal Berangkat</p><p class="font-black text-forest-green">{{ \Illuminate\Support\Carbon::parse($reservasi->jadwal?->tanggal_berangkat)->translatedFormat('d M Y') }}</p></div>
                        <div class="rounded-xl bg-background-light p-4"><p class="text-forest-green/50 text-xs font-black uppercase tracking-[0.25em] mb-2">Jumlah Peserta</p><p class="font-black text-forest-green">{{ $reservasi->jml_peserta }}</p></div>
                        <div class="rounded-xl bg-background-light p-4"><p class="text-forest-green/50 text-xs font-black uppercase tracking-[0.25em] mb-2">Total Bayar</p><p class="font-black text-forest-green">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</p></div>
                        <div class="rounded-xl bg-background-light p-4"><p class="text-forest-green/50 text-xs font-black uppercase tracking-[0.25em] mb-2">Order ID</p><p class="font-black text-forest-green break-all">{{ $pembayaran->orderId }}</p></div>
                    </div>
                </div>

                <div class="rounded-2xl border border-forest-green/10 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-display font-black uppercase text-forest-green">Peserta Trip</h2>
                    <div class="mt-6 space-y-4">
                        @foreach($reservasi->peserta as $peserta)
                            <div class="rounded-xl border border-forest-green/10 p-4 bg-background-light">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-[0.25em] text-primary">Peserta</p>
                                        <h3 class="mt-1 text-lg font-black text-forest-green">{{ $peserta->nama }}</h3>
                                    </div>
                                    <span class="text-xs font-black uppercase tracking-[0.2em] px-3 py-1 rounded-full bg-white text-forest-green">{{ $peserta->jenis_identitas }}</span>
                                </div>
                                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-forest-green/70">
                                    <p>No identitas: {{ $peserta->no_identitas }}</p>
                                    <p>Jenis kelamin: {{ $peserta->jenis_kelamin }}</p>
                                    <p>Tanggal lahir: {{ \Illuminate\Support\Carbon::parse($peserta->tanggal_lahir)->translatedFormat('d M Y') }}</p>
                                    <p>No HP: {{ $peserta->no_hp ?? '-' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="rounded-2xl border border-forest-green/10 bg-white p-6 shadow-lg space-y-6">
                    <div class="space-y-2">
                        <p class="text-xs font-black uppercase tracking-[0.25em] text-forest-green/50">Reservation Status</p>
                        <h2 class="text-2xl font-display font-black uppercase text-forest-green">Tindak Lanjut</h2>
                    </div>

                    <div class="rounded-xl bg-background-light p-4 text-sm text-forest-green/70 space-y-3">
                        <p>Jika status masih <span class="font-black text-forest-green">pending</span>, silakan selesaikan pembayaran terlebih dahulu.</p>
                        <p>Jika status sudah <span class="font-black text-forest-green">paid</span>, reservasi Anda sudah masuk proses verifikasi dan tiket akan disiapkan.</p>
                    </div>

                    <div class="flex flex-col gap-3">
                        @if(! in_array($pembayaran->status, ['settlement', 'capture'], true) && $reservasi->status !== 'paid')
                            <a href="{{ route('reservasi.pembayaran', $reservasi->kode_reservasi) }}" class="w-full rounded-xl bg-forest-green py-3 px-4 text-white font-black uppercase tracking-[0.25em] text-sm hover:bg-forest-green/90 transition text-center">Lanjut ke Pembayaran</a>
                        @endif
                        <a href="{{ route('paket-trip.index') }}" class="w-full rounded-xl border border-forest-green/10 py-3 px-4 text-center text-sm font-black uppercase tracking-[0.25em] text-forest-green hover:border-primary/40 transition">Kembali ke Katalog</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
