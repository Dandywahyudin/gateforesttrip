<section class="relative flex h-screen min-h-[700px] w-full items-center overflow-hidden bg-gray-900">
    
    <!-- Background Image & Overlays -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 z-10 bg-gradient-to-r from-black/90 via-black/50 to-transparent"></div>
        <!-- Background utama -->
        <img 
            src="{{ asset('images/background/background.webp') }}" 
            alt="Gate Forest Trip Background" 
            class="h-full w-full scale-105 transform object-cover"
        >
    </div>

    <!-- Decorative Blurs -->
    <div class="pointer-events-none absolute inset-0 z-[5] overflow-hidden opacity-80">
        <div class="absolute -left-20 -top-20 h-72 w-72 rounded-full bg-primary/20 blur-[100px]"></div>
    </div>

    <!-- Main Content Container -->
    <div class="relative z-20 mx-auto flex w-full max-w-[1440px] items-center justify-between px-6 lg:px-16">
        
        <!-- Text Content (Kiri) -->
        <div class="max-w-2xl space-y-6">

            <h1 class="text-4xl font-bold leading-[1.1] tracking-tight text-white sm:text-5xl lg:text-6xl">
                Jelajahi Alam Bersama
                <br>
                <span class="font-black uppercase tracking-wides text-primary">GATEFORESTTRIP</span>
            </h1>

            <p class="max-w-lg text-base leading-relaxed text-gray-300 sm:text-lg">
                Nikmati pengalaman eksplorasi alam yang aman, nyaman, dan penuh kesan. Tinggalkan rutinitas dan mulai petualangan Anda hari ini.
            </p>

            <div class="pt-4">
                <a href="{{ route('paket-trip.index') }}" class="inline-flex h-12 items-center justify-center rounded-full bg-primary px-8 text-sm font-semibold text-white shadow-lg transition-all hover:scale-105 hover:bg-primary/90">
                    Mulai Jelajah
                </a>
            </div>
        </div>

        <!-- Image Collage (Kanan - 4 Gambar Kecil Estetik) -->
        <!-- Lebar total diperkecil menjadi 440px agar gambar menjadi kecil -->
        <div class="relative z-10 hidden w-[440px] xl:flex gap-4">
            
            <!-- Kolom 1 (Kiri) - Posisinya diturunkan menggunakan pt-16 -->
            <div class="flex w-1/2 flex-col gap-4 pt-16">
                <!-- Gambar 1 (Lebih tinggi) -->
                <div class="overflow-hidden rounded-2xl border border-white/20 bg-white/10 shadow-xl backdrop-blur-md aspect-[4/5]">
                    <img src="{{ asset('images/hero/hero1.webp') }}" alt="Petualangan 1" class="h-full w-full object-cover transition-transform duration-700 hover:scale-110">
                </div>
                <!-- Gambar 2 (Kotak) -->
                <div class="overflow-hidden rounded-2xl border border-white/20 bg-white/10 shadow-xl backdrop-blur-md aspect-square">
                    <img src="{{ asset('images/hero/hero2.webp') }}" alt="Petualangan 2" class="h-full w-full object-cover transition-transform duration-700 hover:scale-110">
                </div>
            </div>

            <!-- Kolom 2 (Kanan) - Posisinya dinaikkan (default ke atas) dan diakhiri pb-16 -->
            <div class="flex w-1/2 flex-col gap-4 pb-16">
                <!-- Gambar 3 (Kotak) -->
                <div class="overflow-hidden rounded-2xl border border-white/20 bg-white/10 shadow-xl backdrop-blur-md aspect-square">
                    <img src="{{ asset('images/hero/hero3.webp') }}" alt="Petualangan 3" class="h-full w-full object-cover transition-transform duration-700 hover:scale-110">
                </div>
                <!-- Gambar 4 (Lebih tinggi) -->
                <div class="overflow-hidden rounded-2xl border border-white/20 bg-white/10 shadow-xl backdrop-blur-md aspect-[4/5]">
                    <img src="{{ asset('images/hero/hero4.webp') }}" alt="Petualangan 4" class="h-full w-full object-cover transition-transform duration-700 hover:scale-110">
                </div>
            </div>

        </div>

    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-10 right-16 z-20 hidden flex-col items-center gap-4 text-white/50 lg:flex">
        <span class="text-[10px] font-semibold uppercase tracking-[0.5em] [writing-mode:vertical-lr]">Scroll Explore</span>
        <div class="h-16 w-[2px] bg-gradient-to-b from-white/50 to-transparent"></div>
    </div>
</section>