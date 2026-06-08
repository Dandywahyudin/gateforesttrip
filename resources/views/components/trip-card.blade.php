@props(['image' => null, 'category', 'title', 'description', 'price', 'paket' => null])

<a href="{{ $paket ? route('paket-trip.show', $paket) : '#' }}" class="bg-white rounded-2xl overflow-hidden group hover:shadow-2xl transition-all duration-500 flex flex-col h-full">
    <div class="relative aspect-[4/3] overflow-hidden">
        @if($image)
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110" style='background-image: url("{{ $image }}");'></div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-forest-green/15 via-white to-primary/15"></div>
            <div class="absolute inset-0 flex items-center justify-center px-6 text-center">
                <div class="space-y-2">
                    <div class="mx-auto flex size-14 items-center justify-center rounded-full bg-white/80 text-forest-green shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-[0.28em] text-forest-green/50">Foto belum tersedia</p>
                </div>
            </div>
        @endif
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
            <span class="inline-flex h-12 items-center justify-center rounded-full bg-primary px-5 text-[10px] font-black uppercase tracking-[0.28em] text-white transition-all group-hover:bg-primary-dark hover:shadow-lg">
                Detail
            </span>
        </div>
    </div>
</a>
