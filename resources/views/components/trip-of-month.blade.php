@props(['highlightPaket' => null, 'featuredPakets' => collect()])

<section class="w-full bg-white py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="flex flex-col items-center gap-12 lg:flex-row lg:items-center">
            
            <!-- Sisi Kiri: 1 Gambar Penuh -->
            <div class="w-full lg:w-1/2 flex justify-center">
                <div class="w-full max-w-md overflow-hidden rounded-2xl md:rounded-[2.5rem] bg-gray-200 shadow-xl aspect-[4/5] lg:aspect-[3/4]">
                    <img src="{{ asset('images/background/GATEFORESTTRIP.webp') }}"
                        alt="Petualangan Alam Gate Forest Trip"
                        class="h-full w-full object-cover transition-transform duration-700 hover:scale-105">
                </div>
            </div>

            <!-- Sisi Kanan: Teks & Daftar Keunggulan -->
            <div class="w-full lg:w-1/2 flex flex-col pt-4 lg:pt-0">
                
                <h2 class="text-2xl sm:text-5xl font-bold text-gray-900 leading-[1.2] mb-12">
                    Kenapa Pilih <br /> GateForestTrip?
                </h2>

                <div class="flex flex-col gap-10">
                    
                    <!-- Poin 1 -->
                    <div class="flex gap-6 items-start group">
                        <div class="flex-shrink-0 w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center transition-colors group-hover:bg-primary/10">
                            <!-- Icon Map/Compass -->
                            <svg class="w-8 h-8 text-gray-500 group-hover:text-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Banyak Pilihan Destinasi</h3>
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                                Mau liburan santai di hutan pinus, camping, canyoneering ataupun jelajah curug semuanya ada di Gate Forest Trip dengan rute yang aman.
                            </p>
                        </div>
                    </div>

                    <!-- Poin 2 -->
                    <div class="flex gap-6 items-start group">
                        <div class="flex-shrink-0 w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center transition-colors group-hover:bg-primary/10">
                            <!-- Icon Wallet/Payment -->
                            <svg class="w-8 h-8 text-gray-500 group-hover:text-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Banyak Metode Pembayaran</h3>
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                                Gak usah pusing, kami menyediakan banyak metode pembayaran instan dan otomatis yang bakal bikin kamu lebih nyaman.
                            </p>
                        </div>
                    </div>

                    <!-- Poin 3 -->
                    <div class="flex gap-6 items-start group">
                        <div class="flex-shrink-0 w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center transition-colors group-hover:bg-primary/10">
                            <!-- Icon Security/Lock -->
                            <svg class="w-8 h-8 text-gray-500 group-hover:text-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Transaksi & Aktivitas Aman</h3>
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                                Keamanan privasi transaksi reservasi online dan keselamatan Anda selama trip menjadi prioritas utama kami.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>
