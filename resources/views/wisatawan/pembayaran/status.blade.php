@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 pt-24 pb-12">
    <div class="max-w-3xl mx-auto px-4">
        <!-- Success Status -->
        @if($pembayaran->status === 'settlement')
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-400 to-green-600 px-8 py-12 text-center text-white">
                    <svg class="w-16 h-16 mx-auto mb-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h1 class="text-4xl font-bold">Pembayaran Berhasil!</h1>
                    <p class="text-green-100 mt-2">Terima kasih telah melakukan pembayaran</p>
                </div>

                <!-- Content -->
                <div class="p-8 space-y-8">
                    <!-- Payment Confirmation -->
                    <div class="border-l-4 border-green-500 bg-green-50 p-6 rounded">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Pembayaran</h2>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Nomor Order</p>
                                <p class="font-mono font-bold text-gray-800">{{ $pembayaran->orderId }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Tanggal Pembayaran</p>
                                <p class="font-semibold text-gray-800">{{ $pembayaran->paid_at?->locale('id')->format('d F Y H:i') ?? 'Pending' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Kode Reservasi</p>
                                <p class="font-mono font-bold text-primary">{{ $reservasi->kode_reservasi }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Metode Pembayaran</p>
                                <p class="font-semibold text-gray-800">
                                    @if($pembayaran->metode_pembayaran === 'midtrans')
                                        Midtrans Payment Gateway
                                    @else
                                        {{ ucfirst($pembayaran->metode_pembayaran ?? 'Unknown') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Reservation Details -->
                    <div class="border-l-4 border-blue-500 bg-blue-50 p-6 rounded">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail Pesanan</h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-700">Paket Wisata:</span>
                                <span class="font-semibold text-gray-800">{{ $reservasi->jadwal->paketTrip->nama }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-700">Kategori:</span>
                                <span class="font-semibold text-gray-800">{{ $reservasi->jadwal->paketTrip->kategori }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-700">Lokasi:</span>
                                <span class="font-semibold text-gray-800">{{ $reservasi->jadwal->paketTrip->lokasi }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-700">Durasi:</span>
                                <span class="font-semibold text-gray-800">{{ $reservasi->jadwal->paketTrip->durasi_hari }} Hari</span>
                            </div>

                            <div class="flex justify-between border-t border-blue-200 pt-3 mt-3">
                                <span class="text-gray-700">Tanggal Berangkat:</span>
                                <span class="font-semibold text-gray-800">
                                    @if(is_string($reservasi->jadwal->tanggal_berangkat))
                                        {{ \Carbon\Carbon::parse($reservasi->jadwal->tanggal_berangkat)->locale('id')->format('d F Y') }}
                                    @else
                                        {{ $reservasi->jadwal->tanggal_berangkat->locale('id')->format('d F Y') }}
                                    @endif
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-700">Tanggal Kembali:</span>
                                <span class="font-semibold text-gray-800">
                                    @if(is_string($reservasi->jadwal->tanggal_kembali))
                                        {{ \Carbon\Carbon::parse($reservasi->jadwal->tanggal_kembali)->locale('id')->format('d F Y') }}
                                    @else
                                        {{ $reservasi->jadwal->tanggal_kembali->locale('id')->format('d F Y') }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="border-l-4 border-yellow-500 bg-yellow-50 p-6 rounded">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Rincian Pembayaran</h2>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-700">Harga per peserta</span>
                                <span>Rp {{ number_format($reservasi->jadwal->harga_override ?? $reservasi->jadwal->paketTrip->harga, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-700">Jumlah peserta</span>
                                <span>{{ $reservasi->jml_peserta }} orang</span>
                            </div>
                            
                            <div class="flex justify-between text-sm border-t border-yellow-200 pt-2 mt-2">
                                <span class="font-semibold text-gray-800">Total Dibayar</span>
                                <span class="font-bold text-lg text-gray-800">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="bg-gradient-to-r from-primary to-primary-dark rounded-lg p-6 text-white">
                        <h3 class="text-lg font-semibold mb-4">Langkah Berikutnya</h3>
                        <ol class="space-y-3">
                            <li class="flex items-start">
                                <span class="flex items-center justify-center h-8 w-8 rounded-full bg-white bg-opacity-20 mr-3 flex-shrink-0 font-semibold">1</span>
                                <span>Anda akan menerima email konfirmasi pembayaran dan bukti reservasi</span>
                            </li>
                            <li class="flex items-start">
                                <span class="flex items-center justify-center h-8 w-8 rounded-full bg-white bg-opacity-20 mr-3 flex-shrink-0 font-semibold">2</span>
                                <span>Tim kami akan memproses data Anda dan menghubungi untuk detail lebih lanjut</span>
                            </li>
                            <li class="flex items-start">
                                <span class="flex items-center justify-center h-8 w-8 rounded-full bg-white bg-opacity-20 mr-3 flex-shrink-0 font-semibold">3</span>
                                <span>Persiapkan diri Anda dan ikuti petunjuk yang diberikan sebelum tanggal keberangkatan</span>
                            </li>
                        </ol>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4">
                        <a href="{{ route('reservasi.show', $reservasi->reservasiId) }}" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-center">
                            Lihat Detail Pesanan
                        </a>
                        <a href="{{ route('reservasi.index') }}" class="flex-1 px-6 py-3 bg-gradient-to-r from-primary to-primary-dark text-white font-semibold rounded-lg hover:shadow-lg transition text-center">
                            Kembali ke Pesanan Saya
                        </a>
                    </div>
                </div>
            </div>

        <!-- Pending Status -->
        @elseif($pembayaran->status === 'pending')
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 px-8 py-12 text-center text-white">
                    <svg class="w-16 h-16 mx-auto mb-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h1 class="text-4xl font-bold">Pembayaran Menunggu Konfirmasi</h1>
                    <p class="text-yellow-100 mt-2">Silakan selesaikan proses pembayaran Anda</p>
                </div>

                <!-- Content -->
                <div class="p-8 space-y-6">
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded">
                        <p class="text-gray-800">Pembayaran Anda sedang diproses. Silakan tunggu atau lakukan pembayaran lagi melalui tombol di bawah.</p>
                    </div>

                    <div class="flex gap-4">
                        <a href="{{ route('pembayaran.create', $reservasi->reservasiId) }}" class="flex-1 px-6 py-3 bg-gradient-to-r from-primary to-primary-dark text-white font-semibold rounded-lg hover:shadow-lg transition text-center">
                            Coba Pembayaran Lagi
                        </a>
                        <a href="{{ route('reservasi.show', $reservasi->reservasiId) }}" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-center">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

        <!-- Failed Status -->
        @else
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-red-400 to-red-600 px-8 py-12 text-center text-white">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h1 class="text-4xl font-bold">Pembayaran {{ ucfirst($pembayaran->status) }}</h1>
                    <p class="text-red-100 mt-2">Status: {{ ucfirst($pembayaran->status) }}</p>
                </div>

                <!-- Content -->
                <div class="p-8 space-y-6">
                    <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded">
                        <h3 class="font-semibold text-red-800 mb-2">Status Pembayaran Belum Selesai</h3>
                        <p class="text-gray-800 mb-4">Silakan coba lagi atau hubungi customer service kami untuk bantuan lebih lanjut.</p>
                        
                        <div class="bg-white rounded p-4 mt-4">
                            <p class="text-sm text-gray-600"><strong>Nomor Order:</strong> {{ $pembayaran->orderId }}</p>
                            <p class="text-sm text-gray-600"><strong>Status:</strong> {{ ucfirst($pembayaran->status) }}</p>
                            <p class="text-sm text-gray-600"><strong>Jumlah:</strong> Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-600"><strong>Waktu:</strong> {{ $pembayaran->updated_at->locale('id')->format('d F Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <a href="{{ route('pembayaran.create', $reservasi->reservasiId) }}" class="flex-1 px-6 py-3 bg-gradient-to-r from-primary to-primary-dark text-white font-semibold rounded-lg hover:shadow-lg transition text-center">
                            Coba Pembayaran Lagi
                        </a>
                        <a href="{{ route('reservasi.show', $reservasi->reservasiId) }}" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-center">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
