@extends('layouts.app')

@section('title', 'Pembayaran - ' . $reservasi->kode_reservasi)

@section('content')
<section class="w-full pt-32 pb-16 bg-[radial-gradient(circle_at_top_right,_rgba(21,128,61,0.12),_transparent_30%),linear-gradient(to_bottom,_#f8faf7,_#ffffff)]">
    <div class="max-w-[1200px] mx-auto px-6 lg:px-16">
        <div class="mb-8">
            <x-breadcrumbs :items="[
                ['label' => 'Home', 'url' => url('/')],
                ['label' => 'Trip', 'url' => route('paket-trip.index')],
                ['label' => $reservasi->jadwal?->paketTrip?->nama, 'url' => $reservasi->jadwal?->paketTrip ? route('paket-trip.show', $reservasi->jadwal->paketTrip) : null],
                ['label' => 'Pembayaran', 'url' => null],
            ]" />
            <h1 class="mt-2 text-4xl md:text-5xl font-display font-black uppercase text-forest-green">Pembayaran</h1>
            <p class="mt-3 text-forest-green/70 max-w-2xl">Lanjutkan pembayaran untuk menyelesaikan reservasi open trip Anda.</p>
            @if(session('error'))
                <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-7 space-y-6">
                <div class="rounded-2xl border border-forest-green/10 bg-background-light p-6">
                    <h2 class="text-2xl font-display font-black uppercase text-forest-green">Informasi Reservasi</h2>
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div><p class="text-forest-green/50 text-xs font-black uppercase tracking-[0.25em] mb-2">Kode Reservasi</p><p class="font-black text-forest-green">{{ $reservasi->kode_reservasi }}</p></div>
                        <div><p class="text-forest-green/50 text-xs font-black uppercase tracking-[0.25em] mb-2">Paket</p><p class="font-black text-forest-green">{{ $reservasi->jadwal?->paketTrip?->nama }}</p></div>
                        <div><p class="text-forest-green/50 text-xs font-black uppercase tracking-[0.25em] mb-2">Jadwal</p><p class="font-black text-forest-green">{{ \Illuminate\Support\Carbon::parse($reservasi->jadwal?->tanggal_berangkat)->translatedFormat('d M Y') }}</p></div>
                        <div><p class="text-forest-green/50 text-xs font-black uppercase tracking-[0.25em] mb-2">Total Bayar</p><p class="font-black text-forest-green">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</p></div>
                    </div>
                </div>

                <div class="rounded-2xl border border-forest-green/10 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-display font-black uppercase text-forest-green">Cara Pembayaran</h2>
                    <ol class="mt-6 space-y-4 text-sm text-forest-green/70 list-decimal list-inside">
                        <li>Klik tombol bayar untuk membuka halaman Midtrans Snap.</li>
                        <li>Selesaikan pembayaran dengan metode yang Anda pilih.</li>
                        <li>Status akan berubah otomatis setelah notifikasi pembayaran masuk.</li>
                    </ol>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="rounded-2xl border border-forest-green/10 bg-white p-6 shadow-lg space-y-6">
                    <div class="space-y-2">
                        <p class="text-xs font-black uppercase tracking-[0.25em] text-forest-green/50">Payment Summary</p>
                        <h2 class="text-2xl font-display font-black uppercase text-forest-green">{{ $reservasi->jadwal?->paketTrip?->nama }}</h2>
                    </div>

                    <div class="rounded-xl bg-background-light p-4 text-sm text-forest-green/70 space-y-2">
                        <div class="flex justify-between gap-4"><span>Jumlah peserta</span><span class="font-black text-forest-green">{{ $reservasi->jml_peserta }}</span></div>
                        <div class="flex justify-between gap-4"><span>Status pembayaran</span><span class="font-black text-forest-green uppercase">{{ $pembayaran->status }}</span></div>
                        <div class="flex justify-between gap-4 pt-2 border-t border-forest-green/10"><span>Total</span><span class="font-black text-forest-green">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</span></div>
                    </div>

                    @if(in_array($pembayaran->status, ['settlement', 'capture'], true) || $reservasi->status === 'paid')
                        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                            Pembayaran sudah terkonfirmasi. Anda akan diarahkan ke status reservasi.
                        </div>
                    @elseif($pembayaran->snap_token)
                        <button
                            type="button"
                            onclick="payNow()"
                            class="w-full rounded-xl bg-forest-green py-3 px-4 text-white font-black uppercase tracking-[0.25em] text-sm hover:bg-forest-green/90 transition"
                        >
                            Bayar Sekarang
                        </button>
                    @else
                        <div class="rounded-xl border border-dashed border-forest-green/20 p-4 text-sm text-forest-green/60">
                            Token pembayaran belum tersedia. Silakan cek konfigurasi Midtrans atau ulangi halaman ini.
                        </div>
                    @endif

                    <a href="{{ route('reservasi.status', $reservasi->kode_reservasi) }}" class="w-full rounded-xl border border-forest-green/10 py-3 px-4 text-center text-sm font-black uppercase tracking-[0.25em] text-forest-green hover:border-primary/40 transition block">Lihat Status</a>
                </div>
            </div>
        </div>
    </div>
</section>

@if($pembayaran->snap_token)
    <script src="https://app{{ filter_var(config('services.midtrans.is_production'), FILTER_VALIDATE_BOOL) ? '' : '.sandbox' }}.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        const finishUrl = @json(route('reservasi.pembayaran.finish') . '?order_id=' . rawurlencode($pembayaran->orderId));
        const unfinishUrl = @json(route('reservasi.pembayaran.unfinish') . '?order_id=' . rawurlencode($pembayaran->orderId));
        const errorUrl = @json(route('reservasi.pembayaran.error') . '?order_id=' . rawurlencode($pembayaran->orderId));

        function redirectTo(url) {
            if (window.top && window.top.location) {
                window.top.location.replace(url);
                return;
            }

            window.location.replace(url);
        }

        function payNow() {
            snap.pay(@json($pembayaran->snap_token), {
                onSuccess: function () {
                    redirectTo(finishUrl);
                },
                onPending: function () {
                    redirectTo(unfinishUrl);
                },
                onError: function () {
                    redirectTo(errorUrl);
                },
                onClose: function () {
                    redirectTo(unfinishUrl);
                }
            });
        }
    </script>
@endif
@endsection
