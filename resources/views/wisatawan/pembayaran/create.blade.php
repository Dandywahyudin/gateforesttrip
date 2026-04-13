@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 pt-24 pb-12">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Breadcrumb -->
        <div class="mb-8">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('reservasi.index') }}" class="text-primary hover:underline">Pesanan Saya</a>
                <span class="text-gray-400">/</span>
                <a href="{{ route('reservasi.show', $reservasi->reservasiId) }}" class="text-primary hover:underline">Detail Pesanan</a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-700 font-semibold">Pembayaran</span>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Payment Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-8">Konfirmasi Pembayaran</h1>

                    <!-- Reservation Summary -->
                    <div class="border-l-4 border-primary bg-blue-50 p-6 mb-8 rounded">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Pesanan</h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-700">Kode Reservasi:</span>
                                <span class="font-mono font-bold text-primary">{{ $reservasi->kode_reservasi }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-700">Paket Wisata:</span>
                                <span class="font-semibold text-gray-800">{{ $reservasi->jadwal->paketTrip->nama }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-700">Jumlah Peserta:</span>
                                <span class="font-semibold text-gray-800">{{ $reservasi->jml_peserta }} orang</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-700">Tanggal Berangkat:</span>
                                <span class="font-semibold text-gray-800">
                                    @if(is_string($reservasi->jadwal->tanggal_berangkat))
                                        {{ \Carbon\Carbon::parse($reservasi->jadwal->tanggal_berangkat)->locale('id')->format('d F Y') }}
                                    @else
                                        {{ $reservasi->jadwal->tanggal_berangkat->locale('id')->format('d F Y') }}
                                    @endif
                                </span>
                            </div>

                            <div class="border-t border-gray-300 pt-3 mt-3 flex justify-between">
                                <span class="text-gray-700">Harga per Peserta:</span>
                                <span class="font-semibold text-gray-800">Rp {{ number_format($reservasi->jadwal->harga_override ?? $reservasi->jadwal->paketTrip->harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Metode Pembayaran</h3>
                        
                        <div class="bg-gradient-to-r from-cyan-500 to-blue-500 rounded-lg p-6 text-white">
                            <div class="flex items-center space-x-3">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                                </svg>
                                <div>
                                    <p class="font-semibold text-lg">Midtrans Payment Gateway</p>
                                    <p class="text-sm opacity-90">Aman dan terpercaya</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Instructions -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
                        <div class="flex">
                            <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-yellow-800 mb-2">Petunjuk Pembayaran</h4>
                                <ul class="text-sm text-yellow-700 space-y-1">
                                    <li>✓ Klik tombol "Bayar Sekarang" untuk melanjutkan</li>
                                    <li>✓ Pilih metode pembayaran yang diinginkan</li>
                                    <li>✓ Ikuti proses pembayaran hingga selesai</li>
                                    <li>✓ Anda akan menerima konfirmasi pembayaran via email</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4">
                        <a href="{{ route('reservasi.show', $reservasi->reservasiId) }}" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-center">
                            Kembali
                        </a>
                        <button id="pay-button" class="flex-1 px-6 py-3 bg-gradient-to-r from-primary to-primary-dark text-white font-semibold rounded-lg hover:shadow-lg transition">
                            Bayar Sekarang
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Card (Sticky) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-28">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Total Pembayaran</h3>
                    
                    <div class="space-y-4 mb-6 border-b border-gray-200 pb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Harga per peserta</span>
                            <span class="font-semibold">Rp {{ number_format($reservasi->jadwal->harga_override ?? $reservasi->jadwal->paketTrip->harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Jumlah peserta</span>
                            <span class="font-semibold">{{ $reservasi->jml_peserta }} x</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Biaya admin</span>
                            <span class="font-semibold">Rp 0</span>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-primary to-primary-dark bg-clip-text">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-semibold">Total</span>
                            <span class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary to-primary-dark">
                                Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-xs text-green-700 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Pembayaran tersegmentasi dengan aman
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap Script -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY', '') }}"></script>

<!-- Payment Script -->
<script>
    const payButton = document.getElementById('pay-button');
    
    payButton.addEventListener('click', function() {
        // Show loading state
        payButton.disabled = true;
        payButton.innerHTML = '<span class="inline-flex items-center"><svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...</span>';
        
        // Create SNAP Token from backend
        // For now, redirect to verification page with dummy data
        // In production, generate actual SNAP token from Midtrans API
        
        window.location.href = '{{ route("pembayaran.verify") }}?order_id={{ $pembayaran->orderId }}&status_code=200&gross_amount={{ $pembayaran->jumlah }}';
    });
</script>
@endsection
