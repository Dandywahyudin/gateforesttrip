@extends('layouts.app')

@section('title', 'Katalog Paket Trip - GateForestTrip')

@section('content')

<!-- Search & Filter Section -->
<section class="w-full pt-32 pb-10 bg-white border-b border-forest-green/10 lg:pt-36">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16">
        <div class="rounded-3xl border border-forest-green/10 bg-forest-green/5 px-5 py-5 shadow-sm lg:px-8 lg:py-6">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 lg:items-end lg:gap-5">
                <!-- Search Input -->
                <div class="lg:col-span-6">
                    <label class="mb-2 block text-xs font-black uppercase tracking-widest text-forest-green/70">
                        Cari Paket Trip
                    </label>
                    <input
                        type="text"
                        id="search-input"
                        placeholder="Ketik nama paket atau destinasi..."
                        class="w-full rounded-2xl border border-forest-green/15 bg-white px-5 py-4 text-forest-green placeholder-forest-green/40 transition-all focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                    />
                </div>

                <!-- Category Filter -->
                <div class="lg:col-span-6">
                    <label class="mb-2 block text-xs font-black uppercase tracking-widest text-forest-green/70">
                        Kategori
                    </label>
                    <select id="category-filter" class="w-full rounded-2xl border border-forest-green/15 bg-white px-5 py-4 font-medium text-forest-green transition-all focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="">Semua Kategori</option>
                        @foreach(\App\Enums\PaketTripKategori::options() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Katalog List -->
<section class="w-full py-14 bg-background-light lg:py-16">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16">
        <!-- Status Display -->
        <div id="status-display" class="mb-6 flex items-center justify-between">
            <p class="text-forest-green/70 font-medium">
                Menampilkan <span id="count-display" class="font-black text-forest-green">{{ count($pakets) }}</span> paket trip
            </p>
        </div>

        <!-- Katalog Grid -->
        <div id="katalog-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
            @forelse($pakets as $paket)
                <div class="paket-card" data-kategori="{{ $paket->kategori_value }}" data-harga="{{ $paket->harga }}">
                    <x-trip-card 
                        :paket="$paket"
                        :image="$paket->foto_url"
                        category="{{ strtoupper($paket->kategori_label ?? 'PAKET AKTIF') }}"
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
            card.style.display = isVisible ? '' : 'none';

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
