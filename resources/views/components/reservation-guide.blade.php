<!-- Pastikan Alpine.js sudah ter-load di layout utama Anda (app.blade.php) -->
<section id="cara-reservasi" class="w-full bg-white py-16 lg:py-24">
    <div class="mx-auto max-w-3xl px-6 lg:px-8">
        
        <h2 class="mb-8 text-2xl font-bold text-gray-900 md:text-3xl">
            Cara Melakukan Reservasi
        </h2>

        <!-- Container Accordion dengan garis pemisah (divide-y) -->
        <div class="divide-y divide-gray-200 border-t border-b border-gray-200">
            
            <!-- Langkah 1 -->
            <div x-data="{ open: false }" class="py-5">
                <button @click="open = !open" type="button" class="flex w-full items-center justify-between text-left focus:outline-none">
                    <span class="text-base font-bold text-gray-900">1. Pilih paket trip</span>
                    <span class="ml-6 flex items-center text-gray-400">
                        <!-- Icon Plus (+) tampil saat tertutup -->
                        <svg x-show="!open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <!-- Icon Silang (X) tampil saat terbuka -->
                        <svg x-show="open" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </span>
                </button>
                <div x-show="open" x-transition.opacity.duration.300ms style="display: none;" class="mt-4 text-sm leading-relaxed text-gray-500">
                    Buka katalog, lihat detail destinasi, harga, fasilitas, meeting point, dan foto perjalanan yang tersedia.
                </div>
            </div>

            <!-- Langkah 2 -->
            <div x-data="{ open: false }" class="py-5">
                <button @click="open = !open" type="button" class="flex w-full items-center justify-between text-left focus:outline-none">
                    <span class="text-base font-bold text-gray-900">2. Masuk sebagai wisatawan</span>
                    <span class="ml-6 flex items-center text-gray-400">
                        <svg x-show="!open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        <svg x-show="open" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </span>
                </button>
                <div x-show="open" x-transition.opacity.duration.300ms style="display: none;" class="mt-4 text-sm leading-relaxed text-gray-500">
                    Login terlebih dahulu agar reservasi, status pembayaran, tiket, dan riwayat pesanan tersimpan di akun Anda.
                </div>
            </div>

            <!-- Langkah 3 -->
            <div x-data="{ open: false }" class="py-5">
                <button @click="open = !open" type="button" class="flex w-full items-center justify-between text-left focus:outline-none">
                    <span class="text-base font-bold text-gray-900">3. Tentukan jadwal</span>
                    <span class="ml-6 flex items-center text-gray-400">
                        <svg x-show="!open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        <svg x-show="open" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </span>
                </button>
                <div x-show="open" x-transition.opacity.duration.300ms style="display: none;" class="mt-4 text-sm leading-relaxed text-gray-500">
                    Pilih tanggal keberangkatan yang masih open, lalu isi jumlah peserta sesuai sisa kuota yang tersedia.
                </div>
            </div>

            <!-- Langkah 4 -->
            <div x-data="{ open: false }" class="py-5">
                <button @click="open = !open" type="button" class="flex w-full items-center justify-between text-left focus:outline-none">
                    <span class="text-base font-bold text-gray-900">4. Isi data peserta</span>
                    <span class="ml-6 flex items-center text-gray-400">
                        <svg x-show="!open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        <svg x-show="open" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </span>
                </button>
                <div x-show="open" x-transition.opacity.duration.300ms style="display: none;" class="mt-4 text-sm leading-relaxed text-gray-500">
                    Lengkapi nama, email, jenis kelamin, tanggal lahir, dan nomor HP peserta sesuai jumlah orang yang ikut.
                </div>
            </div>

            <!-- Langkah 5 -->
            <div x-data="{ open: false }" class="py-5">
                <button @click="open = !open" type="button" class="flex w-full items-center justify-between text-left focus:outline-none">
                    <span class="text-base font-bold text-gray-900">5. Cek ringkasan dan bayar</span>
                    <span class="ml-6 flex items-center text-gray-400">
                        <svg x-show="!open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        <svg x-show="open" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </span>
                </button>
                <div x-show="open" x-transition.opacity.duration.300ms style="display: none;" class="mt-4 text-sm leading-relaxed text-gray-500">
                    Periksa kembali data Anda. Jika sudah sesuai, lanjutkan pembayaran dan hasil transaksi akan otomatis tersimpan di riwayat Anda.
                </div>
            </div>

        </div>
    </div>
</section>