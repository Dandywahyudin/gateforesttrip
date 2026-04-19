@extends('layouts.app')

@section('title', 'Ringkasan - ' . $paket->nama)

@section('content')
<section class="w-full pt-32 pb-16 bg-[radial-gradient(circle_at_top_right,_rgba(21,128,61,0.12),_transparent_30%),linear-gradient(to_bottom,_#f8faf7,_#ffffff)]">
    <div class="max-w-[1200px] mx-auto px-6 lg:px-16">
        <div class="mb-8">
            <x-breadcrumbs :items="[
                ['label' => 'Home', 'url' => url('/')],
                ['label' => 'Trip', 'url' => route('paket-trip.index')],
                ['label' => $paket->nama, 'url' => route('paket-trip.show', $paket)],
                ['label' => 'Pilih Jadwal', 'url' => route('reservasi.jadwal', $paket)],
                ['label' => 'Isi Data', 'url' => route('reservasi.peserta')],
                ['label' => 'Ringkasan', 'url' => null],
            ]" />
            <h1 class="mt-2 text-4xl md:text-5xl font-display font-black uppercase text-forest-green">Ringkasan Reservasi</h1>
            <p class="mt-3 text-forest-green/70 max-w-2xl">Periksa kembali paket, jadwal, peserta, dan total pembayaran sebelum melanjutkan ke checkout.</p>
        </div>

        @if ($errors->has('checkout'))
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700 text-sm font-medium">
                {{ $errors->first('checkout') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-7 space-y-6">
                <div class="rounded-2xl border border-forest-green/10 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-display font-black uppercase text-forest-green">Detail Reservasi</h2>
                    <dl class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="rounded-xl bg-background-light p-4">
                            <dt class="text-forest-green/50 text-xs font-black uppercase tracking-[0.25em] mb-2">Paket Trip</dt>
                            <dd class="font-black text-forest-green">{{ $paket->nama }}</dd>
                        </div>
                        <div class="rounded-xl bg-background-light p-4">
                            <dt class="text-forest-green/50 text-xs font-black uppercase tracking-[0.25em] mb-2">Jadwal</dt>
                            <dd class="font-black text-forest-green">{{ \Illuminate\Support\Carbon::parse($jadwal->tanggal_berangkat)->translatedFormat('d M Y') }}</dd>
                        </div>
                        <div class="rounded-xl bg-background-light p-4">
                            <dt class="text-forest-green/50 text-xs font-black uppercase tracking-[0.25em] mb-2">Jumlah Peserta</dt>
                            <dd class="font-black text-forest-green">{{ $flow['jml_peserta'] }} orang</dd>
                        </div>
                        <div class="rounded-xl bg-background-light p-4">
                            <dt class="text-forest-green/50 text-xs font-black uppercase tracking-[0.25em] mb-2">Total</dt>
                            <dd class="font-black text-forest-green">Rp {{ number_format($flow['total_harga'], 0, ',', '.') }}</dd>
                        </div>
                    </dl>
                    @if(!empty($flow['catatan']))
                        <div class="mt-4 rounded-xl border border-dashed border-forest-green/20 p-4">
                            <p class="text-xs font-black uppercase tracking-[0.25em] text-forest-green/50 mb-2">Catatan</p>
                            <p class="text-sm text-forest-green/70">{{ $flow['catatan'] }}</p>
                        </div>
                    @endif
                </div>

                <div class="rounded-2xl border border-forest-green/10 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-display font-black uppercase text-forest-green">Daftar Peserta</h2>
                    <div class="mt-6 space-y-4">
                        @foreach($flow['data_peserta'] as $index => $peserta)
                            <div class="rounded-xl border border-forest-green/10 p-4 bg-background-light">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-[0.25em] text-primary">Peserta {{ $index + 1 }}</p>
                                        <h3 class="mt-1 text-lg font-black text-forest-green">{{ $peserta['nama'] }}</h3>
                                    </div>
                                </div>
                                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-forest-green/70">
                                    <p>Email: {{ $peserta['email'] ?? '-' }}</p>
                                    <p>Jenis kelamin: {{ $peserta['jenis_kelamin'] }}</p>
                                    <p>Tanggal lahir: {{ \Illuminate\Support\Carbon::parse($peserta['tanggal_lahir'])->translatedFormat('d M Y') }}</p>
                                    <p>No HP: {{ $peserta['no_hp'] ?? '-' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5">
                <form action="{{ route('reservasi.checkout') }}" method="POST" class="rounded-2xl border border-forest-green/10 bg-white p-6 shadow-lg space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <h2 class="text-2xl font-display font-black uppercase text-forest-green">Konfirmasi</h2>
                        <p class="text-sm text-forest-green/60">Klik lanjut untuk membuat reservasi dan masuk ke halaman pembayaran.</p>
                    </div>

                    <div class="rounded-xl bg-background-light p-4 text-sm text-forest-green/70 space-y-2">
                        <div class="flex justify-between gap-4"><span>Harga per peserta</span><span class="font-black text-forest-green">Rp {{ number_format($flow['harga_per_pax'], 0, ',', '.') }}</span></div>
                        <div class="flex justify-between gap-4"><span>Total peserta</span><span class="font-black text-forest-green">{{ $flow['jml_peserta'] }}</span></div>
                        <div class="flex justify-between gap-4 pt-2 border-t border-forest-green/10"><span>Total bayar</span><span class="font-black text-forest-green">Rp {{ number_format($flow['total_harga'], 0, ',', '.') }}</span></div>
                    </div>

                    <div class="rounded-xl border border-dashed border-forest-green/20 p-4 text-sm text-forest-green/60">
                        Pastikan data peserta sudah benar sebelum checkout. Setelah checkout, data akan tersimpan dan Anda diarahkan ke halaman pembayaran.
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit" class="w-full rounded-xl bg-forest-green py-3 px-4 text-white font-black uppercase tracking-[0.25em] text-sm hover:bg-forest-green/90 transition">Lanjut Ke Pembayaran</button>
                        <a href="{{ route('reservasi.peserta') }}" class="w-full rounded-xl border border-forest-green/10 py-3 px-4 text-center text-sm font-black uppercase tracking-[0.25em] text-forest-green hover:border-primary/40 transition">Kembali ke Peserta</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
