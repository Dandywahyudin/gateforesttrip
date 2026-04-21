@php
    $heroImages = array_values(array_filter([
        $highlightPaket?->foto_url,
        $highlightPaket?->foto2_url,
        $highlightPaket?->foto3_url,
    ]));
@endphp

<section class="relative h-[1024px] min-h-[800px] w-full flex items-center overflow-hidden pt-14 md:pt-0">
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/30 to-transparent z-10"></div>
        <div class="w-full h-full bg-cover bg-center scale-105" style='background-image: url("https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=800&fit=crop");'></div>
    </div>

    <div class="absolute inset-0 z-[5] opacity-90">
        <div class="absolute left-[-6rem] top-[-5rem] h-72 w-72 rounded-full bg-primary/20 blur-3xl"></div>
        <div class="absolute right-[-4rem] bottom-[-4rem] h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute right-8 top-1/2 hidden w-[520px] -translate-y-1/2 xl:block">
            @if(count($heroImages))
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-7 overflow-hidden rounded-[34px] border border-white/15 bg-white/5 shadow-[0_26px_80px_rgba(0,0,0,0.28)] backdrop-blur-sm">
                        <div class="aspect-[4/5] w-full">
                            <img src="{{ $heroImages[0] }}" alt="{{ $highlightPaket?->nama ?? 'Hero trip' }}" class="h-full w-full object-cover">
                        </div>
                    </div>
                    <div class="col-span-5 flex flex-col gap-4">
                        <div class="overflow-hidden rounded-[28px] border border-white/15 bg-white/5 shadow-[0_20px_60px_rgba(0,0,0,0.22)] backdrop-blur-sm">
                            <div class="aspect-[4/3] w-full">
                                @if(isset($heroImages[1]))
                                    <img src="{{ $heroImages[1] }}" alt="{{ $highlightPaket?->nama ?? 'Hero trip' }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-white/5 px-6 text-center text-white/80">
                                        <span class="text-[10px] font-black uppercase tracking-[0.3em]">Foto 2 belum tersedia</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="overflow-hidden rounded-[28px] border border-white/15 bg-white/5 shadow-[0_20px_60px_rgba(0,0,0,0.22)] backdrop-blur-sm">
                            <div class="aspect-[4/3] w-full">
                                @if(isset($heroImages[2]))
                                    <img src="{{ $heroImages[2] }}" alt="{{ $highlightPaket?->nama ?? 'Hero trip' }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-white/5 px-6 text-center text-white/80">
                                        <span class="text-[10px] font-black uppercase tracking-[0.3em]">Foto 3 belum tersedia</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex h-full items-center justify-center rounded-[34px] border border-white/15 bg-white/5 px-8 py-10 text-center text-white/80 shadow-[0_26px_80px_rgba(0,0,0,0.2)] backdrop-blur-sm">
                    <div class="space-y-3 max-w-sm">
                        <p class="text-[10px] font-black uppercase tracking-[0.35em] text-white/50">Foto belum diunggah</p>
                        <p class="text-sm leading-relaxed">Unggah foto paket unggulan agar hero tampil lebih hidup dan berlapis.</p>
                    </div>
                </div>
            @endif
        </div>
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/35 to-transparent"></div>
    </div>

    <div class="relative z-20 w-full max-w-[1440px] mx-auto px-6 lg:px-16 flex flex-col items-start text-left">
        <div class="max-w-[660px] space-y-5 ml-0 lg:ml-12 -mt-4 lg:-mt-8">


            <h3 class="text-white text-4xl sm:text-5xl md:text-6xl lg:text-[84px] font-display font-black leading-[0.92] uppercase">
                Jelajahi Alam
Bersama
 <br/><span class="text-primary italic">GateForestTrip</span>
            </h3>

            <p class="text-white/78 text-xs sm:text-sm md:text-lg font-light leading-relaxed max-w-[540px] font-subheading">
                Tinggalkan rutinitas dan temukan kemurnian alam yang belum terjamah. Sebuah ekspedisi mendalam menuju jantung Nusantara yang mistis dan megah.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 pt-1">
                <button class="group flex items-center justify-center gap-3 h-14 px-8 rounded-full bg-primary hover:bg-orange-700 text-white text-sm font-black uppercase tracking-widest transition-all shadow-2xl hover:scale-105">
                    RESERVASI SEKARANG
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5 transition-transform group-hover:translate-x-2">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
                <button class="h-14 px-8 rounded-full bg-white/5 backdrop-blur-xl border border-white/20 text-white text-sm font-bold hover:bg-white/10 transition-all uppercase tracking-widest">
                    LIHAT KATALOG
                </button>
            </div>
        </div>
    </div>

    <div class="absolute bottom-12 right-16 z-20 hidden lg:flex flex-col items-center gap-4 text-white/50">
        <span class="text-[10px] font-black uppercase tracking-[0.5em] [writing-mode:vertical-lr]">SCROLL EXPLORE</span>
        <div class="w-[2px] h-20 bg-gradient-to-b from-white/50 to-transparent"></div>
    </div>
</section>
