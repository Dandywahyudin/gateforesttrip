@extends('layouts.app')

@section('title', 'Konfirmasi Reservasi - ' . $reservasi->jadwal->paketTrip->nama)

@section('content')
<div class="min-h-screen bg-background-light pt-24 pb-12">
    <div class="max-w-4xl mx-auto px-6 lg:px-16">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-8 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 font-black flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Confirmation Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-forest-green to-primary p-8 text-white">
                <p class="text-sm opacity-90 uppercase tracking-widest mb-2">Reservasi Berhasil Dibuat</p>
                <h1 class="text-4xl font-display font-black uppercase mb-4">Konfirmasi Reservasi</h1>
                <p class="text-lg font-black">Kode: {{ $reservasi->kode_reservasi }}</p>
            </div>

            <!-- Content -->
            <div class="p-8 space-y-8">
                <!-- Trip Info -->
                <div>
                    <h2 class="text-2xl font-display font-black text-forest-green uppercase mb-6">Detil Paket Wisata</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-sm text-forest-green/60 uppercase tracking-widest mb-1">Nama Paket</p>
                            <p class="text-xl font-black text-forest-green">{{ $reservasi->jadwal->paketTrip->nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-forest-green/60 uppercase tracking-widest mb-1">Kategori</p>
                            <p class="text-xl font-black text-forest-green">{{ $reservasi->jadwal->paketTrip->kategori }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-forest-green/60 uppercase tracking-widest mb-1">Lokasi</p>
                            <p class="text-base text-forest-green/80">{{ $reservasi->jadwal->paketTrip->lokasi }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-forest-green/60 uppercase tracking-widest mb-1">Durasi</p>
                            <p class="text-base text-forest-green/80">{{ $reservasi->jadwal->paketTrip->durasi_hari }} Hari</p>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                <!-- Schedule Info -->
                <div>
                    <h2 class="text-2xl font-display font-black text-forest-green uppercase mb-6">Jadwal Perjalanan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-6 bg-background-light rounded-lg border-l-4 border-primary">
                            <p class="text-sm text-forest-green/60 uppercase tracking-widest mb-1">Tanggal Berangkat</p>
                            <p class="text-2xl font-display font-black text-forest-green">
                                {{ \Carbon\Carbon::parse($reservasi->jadwal->tanggal_berangkat)->format('d M Y') }}
                            </p>
                            <p class="text-xs text-forest-green/60 mt-1">
                                {{ \Carbon\Carbon::parse($reservasi->jadwal->tanggal_berangkat)->format('l') }}
                            </p>
                        </div>
                        <div class="p-6 bg-background-light rounded-lg border-l-4 border-blue-500">
                            <p class="text-sm text-forest-green/60 uppercase tracking-widest mb-1">Tanggal Kembali</p>
                            <p class="text-2xl font-display font-black text-forest-green">
                                {{ \Carbon\Carbon::parse($reservasi->jadwal->tanggal_kembali)->format('d M Y') }}
                            </p>
                            <p class="text-xs text-forest-green/60 mt-1">
                                {{ \Carbon\Carbon::parse($reservasi->jadwal->tanggal_kembali)->format('l') }}
                            </p>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                <!-- Participants -->
                <div>
                    <h2 class="text-2xl font-display font-black text-forest-green uppercase mb-6">Data Peserta</h2>
                    <div class="space-y-3">
                        @foreach($reservasi->peserta as $idx => $peserta)
                            <div class="p-4 border border-gray-200 rounded-lg">
                                <p class="font-black text-forest-green mb-2">Peserta {{ $idx + 1 }}</p>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <p class="text-forest-green/60 uppercase tracking-widest text-xs mb-1">Nama</p>
                                        <p class="font-black text-forest-green">{{ $peserta->nama }}</p>
                                    </div>
                                    <div>
                                        <p class="text-forest-green/60 uppercase tracking-widest text-xs mb-1">Email</p>
                                        <p class="text-forest-green/80">{{ $peserta->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-forest-green/60 uppercase tracking-widest text-xs mb-1">No. HP</p>
                                        <p class="text-forest-green/80">{{ $peserta->no_hp }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <hr class="border-gray-200">

                <!-- Payment Status -->
                <div>
                    <h2 class="text-2xl font-display font-black text-forest-green uppercase mb-6">Status Pembayaran</h2>
                    <div class="p-6 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800 uppercase tracking-widest font-black mb-2">Status</p>
                        <p class="text-2xl font-display font-black text-yellow-800 mb-4">
                            @if($reservasi->status === 'paid')
                                ✓ Sudah Dibayar
                            @elseif($reservasi->status === 'unpaid')
                                ⏳ Menunggu Pembayaran
                            @else
                                ✗ Dibatalkan
                            @endif
                        </p>
                        
                        @if($reservasi->status === 'unpaid')
                            <p class="text-yellow-800 text-sm mb-4">
                                Silakan selesaikan pembayaran dalam waktu 24 jam untuk mengamankan reservasi Anda.
                            </p>
                            <a href="{{ route('pembayaran.create', $reservasi->reservasiId) }}"
                               class="inline-block px-8 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-black uppercase tracking-widest rounded-lg transition-all">
                                Lanjutkan Pembayaran
                            </a>
                        @endif
                    </div>
                </div>

                <hr class="border-gray-200">

                <!-- Price Summary -->
                <div>
                    <h2 class="text-2xl font-display font-black text-forest-green uppercase mb-6">Ringkasan Pembayaran</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <p class="text-forest-green/70">Harga per orang:</p>
                            <p class="font-black text-forest-green">
                                IDR {{ number_format($reservasi->total_harga / $reservasi->jml_peserta / 1000000, 1) }}M
                            </p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-forest-green/70">Jumlah peserta:</p>
                            <p class="font-black text-forest-green">{{ $reservasi->jml_peserta }} orang</p>
                        </div>
                        <div class="border-t-2 border-gray-200 pt-4 flex justify-between items-center">
                            <p class="text-lg font-black text-forest-green uppercase">Total Pembayaran:</p>
                            <p class="text-3xl font-display font-black text-primary">
                                IDR {{ number_format($reservasi->total_harga / 1000000, 1) }}M
                            </p>
                        </div>
                    </div>
                </div>

                @if($reservasi->catatan)
                    <div>
                        <h3 class="font-black text-forest-green mb-2">Catatan Khusus:</h3>
                        <p class="text-forest-green/70 p-4 bg-background-light rounded-lg">{{ $reservasi->catatan }}</p>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="bg-background-light border-t border-gray-200 p-8 flex gap-4">
                <a href="{{ route('paket-trip.index') }}"
                   class="flex-1 py-4 border-2 border-forest-green text-forest-green font-black uppercase tracking-widest rounded-lg hover:bg-forest-green hover:text-white transition-all text-center">
                    Lihat Paket Lain
                </a>
                @if($reservasi->status === 'unpaid')
                    <a href="{{ route('pembayaran.create', $reservasi->reservasiId) }}"
                       class="flex-1 py-4 bg-primary hover:bg-orange-700 text-white font-black uppercase tracking-widest rounded-lg transition-all text-center">
                        Bayar Sekarang
                    </a>
                @endif
            </div>
        </div>

        <!-- Important Notes -->
        <div class="mt-8 p-6 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="font-black text-blue-900 mb-3">⚠️ Penting:</h3>
            <ul class="text-sm text-blue-900 space-y-2 list-disc list-inside">
                <li>Simpan kode reservasi Anda: <span class="font-black">{{ $reservasi->kode_reservasi }}</span></li>
                <li>Selesaikan pembayaran dalam waktu 24 jam untuk mengamankan tempat Anda</li>
                <li>Anda akan menerima email konfirmasi di {{ auth()->user()->email }}</li>
                <li>Hubungi tim kami jika ada pertanyaan atau masalah</li>
            </ul>
        </div>
    </div>
</div>
@endsection
