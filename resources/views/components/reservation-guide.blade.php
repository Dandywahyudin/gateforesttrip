<section id="cara-reservasi" class="w-full bg-white py-24">
    <div class="mx-auto max-w-[1440px] px-6 lg:px-16">
        <div class="mb-14 grid grid-cols-1 gap-8 lg:grid-cols-[0.8fr_1.2fr] lg:items-end">
            <div class="space-y-4">
                <span class="text-xs font-black uppercase tracking-[0.4em] text-primary">Cara Reservasi</span>
                <h2 class="font-display text-4xl font-black uppercase leading-tight text-forest-green md:text-6xl">
                    Booking trip dalam beberapa langkah
                </h2>
            </div>
            <p class="max-w-2xl text-sm leading-relaxed text-forest-green/70 md:text-base lg:ml-auto lg:text-right">
                Ikuti alur reservasi dari halaman paket sampai pembayaran. Pastikan data peserta sudah benar sebelum checkout agar tiket dan bukti transaksi tercatat rapi di akun Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            <div class="rounded-2xl border border-forest-green/10 bg-background-light p-6 shadow-sm">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-forest-green font-display text-xl font-black text-white">1</span>
                    <span class="text-[10px] font-black uppercase tracking-[0.28em] text-forest-green/40">Paket</span>
                </div>
                <h3 class="mb-3 font-display text-2xl font-black uppercase leading-tight text-forest-green">Pilih paket trip</h3>
                <p class="text-sm leading-relaxed text-forest-green/70">Buka katalog, lihat detail destinasi, harga, fasilitas, meeting point, dan foto perjalanan yang tersedia.</p>
            </div>

            <div class="rounded-2xl border border-forest-green/10 bg-background-light p-6 shadow-sm">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-primary font-display text-xl font-black text-white">2</span>
                    <span class="text-[10px] font-black uppercase tracking-[0.28em] text-forest-green/40">Akun</span>
                </div>
                <h3 class="mb-3 font-display text-2xl font-black uppercase leading-tight text-forest-green">Masuk sebagai wisatawan</h3>
                <p class="text-sm leading-relaxed text-forest-green/70">Login terlebih dahulu agar reservasi, status pembayaran, tiket, dan riwayat pesanan tersimpan di akun Anda.</p>
            </div>

            <div class="rounded-2xl border border-forest-green/10 bg-background-light p-6 shadow-sm">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-forest-green font-display text-xl font-black text-white">3</span>
                    <span class="text-[10px] font-black uppercase tracking-[0.28em] text-forest-green/40">Jadwal</span>
                </div>
                <h3 class="mb-3 font-display text-2xl font-black uppercase leading-tight text-forest-green">Tentukan jadwal</h3>
                <p class="text-sm leading-relaxed text-forest-green/70">Pilih tanggal keberangkatan yang masih open, lalu isi jumlah peserta sesuai sisa kuota yang tersedia.</p>
            </div>

            <div class="rounded-2xl border border-forest-green/10 bg-background-light p-6 shadow-sm">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-primary font-display text-xl font-black text-white">4</span>
                    <span class="text-[10px] font-black uppercase tracking-[0.28em] text-forest-green/40">Peserta</span>
                </div>
                <h3 class="mb-3 font-display text-2xl font-black uppercase leading-tight text-forest-green">Isi data peserta</h3>
                <p class="text-sm leading-relaxed text-forest-green/70">Lengkapi nama, email, jenis kelamin, tanggal lahir, dan nomor HP peserta sesuai jumlah orang yang ikut.</p>
            </div>

            <div class="rounded-2xl border border-forest-green/10 bg-background-light p-6 shadow-sm">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-forest-green font-display text-xl font-black text-white">5</span>
                    <span class="text-[10px] font-black uppercase tracking-[0.28em] text-forest-green/40">Checkout</span>
                </div>
                <h3 class="mb-3 font-display text-2xl font-black uppercase leading-tight text-forest-green">Cek ringkasan</h3>
                <p class="text-sm leading-relaxed text-forest-green/70">Periksa paket, jadwal, data peserta, dan total harga. Jika sudah sesuai, lanjutkan ke pembayaran.</p>
            </div>

            <div class="rounded-2xl border border-forest-green/10 bg-forest-green p-6 text-white shadow-[0_22px_70px_rgba(27,67,50,0.18)]">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-white font-display text-xl font-black text-forest-green">6</span>
                    <span class="text-[10px] font-black uppercase tracking-[0.28em] text-white/50">Tiket</span>
                </div>
                <h3 class="mb-3 font-display text-2xl font-black uppercase leading-tight">Bayar dan simpan tiket</h3>
                <p class="text-sm leading-relaxed text-white/75">Selesaikan pembayaran, lalu cek status, bukti transaksi, dan tiket melalui menu riwayat reservasi.</p>
            </div>
        </div>

        <div class="mt-12 flex flex-col gap-4 rounded-2xl border border-forest-green/10 bg-background-light p-6 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-primary">Siap mulai?</p>
                <p class="mt-2 text-base font-bold text-forest-green">Pilih paket yang sesuai, lalu ikuti alur reservasi sampai pembayaran.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('paket-trip.index') }}" class="inline-flex items-center justify-center rounded-full bg-forest-green px-8 py-4 text-xs font-black uppercase tracking-[0.25em] text-white transition hover:bg-forest-green/90">
                    Lihat Paket
                </a>
                @auth
                    @if(auth()->user()->isWisatawan())
                        <a href="{{ route('reservasi.riwayat') }}" class="inline-flex items-center justify-center rounded-full border border-forest-green/15 px-8 py-4 text-xs font-black uppercase tracking-[0.25em] text-forest-green transition hover:border-primary hover:text-primary">
                            Riwayat
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full border border-forest-green/15 px-8 py-4 text-xs font-black uppercase tracking-[0.25em] text-forest-green transition hover:border-primary hover:text-primary">
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>
