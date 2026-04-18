@props(['image', 'category', 'title', 'description', 'price', 'rating' => 4.9, 'paket' => null])

<a href="{{ $paket ? route('paket-trip.show', $paket) : '#' }}" class="bg-white rounded-2xl overflow-hidden group hover:shadow-2xl transition-all duration-500 flex flex-col h-full">
    <div class="relative aspect-[4/3] overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110" style='background-image: url("{{ $image }}");'></div>
        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-md px-3 py-1 rounded-lg flex items-center gap-1 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-primary">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
        </div>
    </div>

    <div class="p-8 flex flex-col flex-1">
        <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-2">{{ $category }}</span>
        <h3 class="text-2xl font-display font-black text-forest-green uppercase mb-4 group-hover:text-primary transition-colors">{{ $title }}</h3>
        <p class="text-forest-green/70 text-sm leading-relaxed mb-8 flex-1">{{ $description }}</p>

        <div class="pt-6 border-t border-forest-green/5 flex items-center justify-between">
            <div>
                <p class="text-[10px] text-forest-green/50 uppercase font-black tracking-widest">Mulai Dari</p>
                <p class="text-xl font-display font-black text-forest-green">{{ $price }}</p>
            </div>
            <button class="size-12 rounded-full border border-forest-green/20 flex items-center justify-center group-hover:bg-primary group-hover:border-primary group-hover:text-white transition-all hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </button>
        </div>
    </div>
</a>
