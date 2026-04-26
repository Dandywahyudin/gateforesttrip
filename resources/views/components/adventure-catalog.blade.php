@props(['pakets' => collect()])

<section class="w-full py-24 bg-background-light">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16">
        <div class="mb-16 flex flex-col md:flex-row md:items-end justify-between gap-8">
            <div class="space-y-4">
                <span class="text-primary font-black tracking-[0.4em] uppercase text-xs">Jelajahi Lebih Banyak</span>
                <h2 class="text-5xl font-display font-black text-forest-green uppercase">KATALOG PETUALANGAN</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <button class="px-8 py-3 rounded-full bg-forest-green text-white font-black text-xs uppercase tracking-widest hover:bg-primary transition-all">Semua</button>
                @foreach($pakets->pluck('kategori_label')->filter()->unique()->values() as $kategori)
                    <button class="px-8 py-3 rounded-full bg-white border border-forest-green/10 text-forest-green font-black text-xs uppercase tracking-widest hover:bg-forest-green hover:text-white transition-all">
                        {{ $kategori }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mb-12">
            @forelse($pakets->take(6) as $paket)
                <x-trip-card 
                    :paket="$paket"
                    :image="$paket->foto_url"
                    category="{{ strtoupper($paket->kategori_label ?? 'PAKET AKTIF') }}"
                    title="{{ $paket->nama }}"
                    description="{{ \Illuminate\Support\Str::limit($paket->deskripsi, 80) }}"
                    price="Rp {{ number_format($paket->harga, 0, ',', '.') }}"
                />
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-forest-green/20 bg-white p-8 text-center text-forest-green/60">
                    Data paket trip belum tersedia.
                </div>
            @endforelse
        </div>

        <div class="text-center">
            <a href="{{ route('paket-trip.index') }}" class="inline-flex px-16 py-6 rounded-full border-2 border-forest-green text-forest-green hover:bg-forest-green hover:text-white font-black text-sm uppercase tracking-[0.3em] transition-all shadow-xl shadow-forest-green/5">
                LIHAT SEMUA JADWAL TRIP
            </a>
        </div>
    </div>
</section>
