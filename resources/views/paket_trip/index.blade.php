@extends('layouts.app')

@section('title', 'Katalog Paket Trip - GateForestTrip')

@section('content')

<!-- Search & Filter Section -->
<section class="w-full py-20 bg-white border-b border-forest-green/10">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16">
        <div class="flex flex-col lg:flex-row gap-6 items-end">
            <!-- Search Input -->
            <div class="flex-1">
                <label class="block text-xs font-black text-forest-green/70 uppercase tracking-widest mb-2">
                    Cari Paket Trip
                </label>
                <input 
                    type="text" 
                    id="search-input"
                    placeholder="Ketik nama paket atau destinasi..."
                    class="w-full px-6 py-4 rounded-lg border border-forest-green/20 bg-forest-green/5 text-forest-green placeholder-forest-green/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                />
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-xs font-black text-forest-green/70 uppercase tracking-widest mb-2">
                    Kategori
                </label>
                <select id="category-filter" class="px-6 py-4 rounded-lg border border-forest-green/20 bg-white text-forest-green font-medium focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                    <option value="">Semua Kategori</option>
                    <option value="gunung">Gunung</option>
                    <option value="hutan">Hutan</option>
                    <option value="pantai">Pantai</option>
                    <option value="Trekking">Trekking</option>
                    <option value="Ca">Canyoneering</option>
                    <option value="camping">Camping</option>
                </select>
            </div>

            <!-- Price Range -->
            <div>
                <label class="block text-xs font-black text-forest-green/70 uppercase tracking-widest mb-2">
                    Harga
                </label>
                <select id="price-filter" class="px-6 py-4 rounded-lg border border-forest-green/20 bg-white text-forest-green font-medium focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                    <option value="">Semua Harga</option>
                    <option value="0-1000000">Rp 0 - 1 Juta</option>
                    <option value="1000000-2500000">Rp 1 - 2.5 Juta</option>
                    <option value="2500000-5000000">Rp 2.5 - 5 Juta</option>
                    <option value="5000000">Rp 5+ Juta</option>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- Katalog List -->
<section class="w-full py-24 bg-background-light">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16">
        <!-- Status Display -->
        <div id="status-display" class="mb-8 flex items-center justify-between">
            <p class="text-forest-green/70 font-medium">
                Menampilkan <span id="count-display" class="font-black text-forest-green">{{ count($pakets) }}</span> paket trip
            </p>
        </div>

        <!-- Katalog Grid -->
        <div id="katalog-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            @forelse($pakets as $paket)
                <div class="paket-card" data-kategori="{{ strtolower($paket->kategori) }}" data-harga="{{ $paket->harga }}">
                    <x-trip-card 
                        :paket="$paket"
                        :image="$paket->foto_url"
                        category="{{ strtoupper($paket->kategori) }}"
                        title="{{ $paket->nama }}"
                        description="{{ Str::limit($paket->deskripsi, 80) }}"
                        price="Rp {{ number_format($paket->harga, 0, ',', '.') }}"
                    />
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 mx-auto text-forest-green/20 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="text-2xl font-black text-forest-green mb-2">Tidak Ada Paket</h3>
                    <p class="text-forest-green/60">Maaf, tidak ada paket trip yang sesuai dengan pencarian Anda.</p>
                </div>
            @endforelse
        </div>

        <!-- Empty State (No Results) -->
        <div id="empty-state" class="hidden col-span-full text-center py-20">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 mx-auto text-forest-green/20 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="text-2xl font-black text-forest-green mb-2">Tidak Ada Hasil</h3>
            <p class="text-forest-green/60">Coba ubah filter atau kata kunci pencarian Anda.</p>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="w-full py-20 bg-forest-green text-white">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16 text-center space-y-8">
        <h2 class="text-4xl md:text-5xl font-display font-black uppercase">Siap untuk Petualangan?</h2>
        <p class="text-lg text-white/80 max-w-2xl mx-auto">
            Jangan lewatkan momen spesial bersama komunitas petualang. Daftar sekarang dan dapatkan early bird discount hingga 20%!
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('login') }}" class="px-12 py-4 bg-white text-forest-green font-black uppercase tracking-widest rounded-full hover:shadow-2xl transition-all">
                Mulai Sekarang
            </a>
            <button class="px-12 py-4 border-2 border-white text-white font-black uppercase tracking-widest rounded-full hover:bg-white hover:text-forest-green transition-all">
                Hubungi Kami
            </button>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Filter Functionality
    const searchInput = document.getElementById('search-input');
    const categoryFilter = document.getElementById('category-filter');
    const priceFilter = document.getElementById('price-filter');
    const paketCards = document.querySelectorAll('.paket-card');
    const countDisplay = document.getElementById('count-display');
    const katalogGrid = document.getElementById('katalog-grid');
    const emptyState = document.getElementById('empty-state');

    function filterPakets() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value.toLowerCase();
        const selectedPrice = priceFilter.value;

        let visibleCount = 0;

        paketCards.forEach(card => {
            const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const description = card.querySelector('p')?.textContent.toLowerCase() || '';
            const category = card.getAttribute('data-kategori');
            const price = parseFloat(card.getAttribute('data-harga'));

            let matchSearch = title.includes(searchTerm) || description.includes(searchTerm);
            let matchCategory = !selectedCategory || category === selectedCategory;
            let matchPrice = true;

            if (selectedPrice) {
                const [minStr, maxStr] = selectedPrice.split('-');
                const min = parseInt(minStr);
                const max = maxStr ? parseInt(maxStr) : Infinity;
                matchPrice = price >= min && price <= max;
            }

            const isVisible = matchSearch && matchCategory && matchPrice;
            card.style.display = isVisible ? 'block' : 'none';

            if (isVisible) visibleCount++;
        });

        // Update count and empty state
        countDisplay.textContent = visibleCount;
        emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    // Event Listeners
    searchInput.addEventListener('input', filterPakets);
    categoryFilter.addEventListener('change', filterPakets);
    priceFilter.addEventListener('change', filterPakets);
</script>
@endpush
@endsection
