<header class="fixed top-0 z-[100] w-full bg-black/10 backdrop-blur-md border-b border-white/5 transition-all duration-300">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16 py-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            {{-- <div class="size-10 text-primary flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                </svg>
            </div> --}}
            <h1 class="text-2xl font-black font-display uppercase text-white">GateForestTrip</h1>
        </div>

        <nav class="hidden md:flex items-center gap-10">
            <a class="text-xs font-bold text-black hover:text-primary transition-colors uppercase tracking-[0.2em]" href="/">Beranda</a>
            <a class="text-xs font-bold text-black/70 hover:text-primary transition-colors uppercase tracking-[0.2em]" href="{{ route('paket-trip.index') }}">Katalog</a>
            <a class="text-xs font-bold text-black/70 hover:text-primary transition-colors uppercase tracking-[0.2em]" href="#destinations">Destinasi</a>
        </nav>

        <div class="flex items-center gap-6">
            <a href="{{ route('login') }}"
                class="hidden sm:flex px-8 h-12 items-center justify-center rounded-full bg-primary hover:bg-orange-700 text-white text-xs font-black uppercase tracking-widest transition-all shadow-xl shadow-primary/20">
                    Login
                </a>
            <button class="md:hidden p-2 text-white hover:text-primary transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>
</header>
