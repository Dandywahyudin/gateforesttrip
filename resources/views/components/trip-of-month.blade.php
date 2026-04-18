@props(['tripOfTheMonth' => null, 'featuredPakets' => collect()])

<section class="w-full py-32 bg-white">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16">
        <div class="flex flex-col md:flex-row items-end justify-between mb-16 gap-4">
            <div class="space-y-4">
                <span class="text-primary font-black tracking-[0.4em] uppercase text-xs">Pilihan Eksklusif Bulan Ini</span>
                <h2 class="text-5xl md:text-7xl font-display font-black text-forest-green uppercase">TRIP OF THE MONTH</h2>
            </div>
            <p class="text-forest-green/60 max-w-[400px] text-right font-medium">Pengalaman perjalanan paling dicari, dikurasi secara khusus dari paket yang memang sudah aktif di database.</p>
        </div>

        @if($tripOfTheMonth)
            <div class="relative w-full rounded-2xl overflow-hidden shadow-[0_30px_100px_rgba(27,67,50,0.15)] group mb-12">
                <div class="aspect-[21/9] min-h-[500px] w-full bg-cover bg-center transition-transform duration-1000 group-hover:scale-105" style='background-image: url("{{ $tripOfTheMonth->foto_url ?? 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=500&fit=crop' }}");'></div>

                <div class="absolute inset-0 bg-gradient-to-t from-forest-green via-forest-green/20 to-transparent"></div>

                <div class="absolute bottom-0 left-0 w-full p-8 md:p-16 flex flex-col md:flex-row items-end justify-between gap-8">
                    <div class="space-y-6 max-w-[800px]">
                        <div class="flex gap-4 flex-wrap">
                            <span class="bg-primary text-white text-[10px] font-black uppercase tracking-widest px-6 py-2 rounded-full">{{ $tripOfTheMonth->kategori ?: 'Paket Aktif' }}</span>
                            <span class="bg-white/20 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest px-6 py-2 rounded-full border border-white/20">{{ $tripOfTheMonth->jadwal_open_count ?? 0 }} jadwal open</span>
                        </div>

                        <h3 class="text-4xl md:text-7xl font-display font-black text-white uppercase leading-none">{{ $tripOfTheMonth->nama }}</h3>

                        <p class="text-white/70 text-lg max-w-[600px] font-light">{{ \Illuminate\Support\Str::limit($tripOfTheMonth->deskripsi, 180) }}</p>
                    </div>

                    <div class="flex flex-col items-end gap-6 w-full md:w-auto">
                        <div class="text-right">
                            <p class="text-white/50 text-xs font-black uppercase tracking-widest mb-1">Mulai Dari</p>
                            <p class="text-4xl font-display font-black text-primary">Rp {{ number_format($tripOfTheMonth->harga, 0, ',', '.') }}</p>
                        </div>
                        <a href="{{ route('paket-trip.show', $tripOfTheMonth) }}" class="w-full md:w-auto h-16 px-12 inline-flex items-center justify-center bg-white text-forest-green font-black uppercase tracking-widest text-sm hover:bg-primary hover:text-white transition-all rounded-full shadow-xl">
                            AMANKAN SLOT
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($featuredPakets as $paket)
                <x-trip-card
                    :paket="$paket"
                    image="{{ $paket->foto_url ?? 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=500&h=300&fit=crop' }}"
                    category="{{ $paket->kategori ? strtoupper($paket->kategori) : 'PAKET AKTIF' }}"
                    title="{{ $paket->nama }}"
                    description="{{ \Illuminate\Support\Str::limit($paket->deskripsi, 90) }}"
                    price="Rp {{ number_format($paket->harga, 0, ',', '.') }}"
                    
                />
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-forest-green/20 bg-forest-green/5 p-8 text-center text-forest-green/60">
                    Belum ada paket trip aktif untuk ditampilkan.
                </div>
            @endforelse
        </div>
    </div>
</section>
