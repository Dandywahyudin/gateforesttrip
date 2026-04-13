@extends('layouts.app')

@section('title', $paket->nama . ' - GateForestTrip')

@section('content')
<!-- Hero Section with Image Gallery & Booking Card -->
<section class="w-full bg-white pt-24">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left: Image Gallery -->
            <div class="space-y-3">
                <!-- Main Image -->
                <div class="rounded-lg overflow-hidden shadow-lg h-[300px] md:h-[400px]">
                    <img id="gallery-main" src="{{ $paket->foto ?? 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=600&fit=crop' }}" 
                         alt="{{ $paket->nama }}" class="w-full h-full object-cover cursor-zoom-in transition-transform hover:scale-105 duration-300">
                </div>

                <!-- Thumbnails -->
                <div class="grid grid-cols-4 gap-2">
                    <div class="gallery-thumb rounded-lg overflow-hidden cursor-pointer border-2 border-primary h-20" 
                         onclick="updateGallery('{{ $paket->foto ?? 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=600&fit=crop' }}')">
                        <img src="{{ $paket->foto ?? 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=200&h=200&fit=crop' }}" 
                             alt="Foto 1" class="w-full h-full object-cover hover:scale-110 transition-transform">
                    </div>
                    <div class="gallery-thumb rounded-lg overflow-hidden cursor-pointer border-2 border-forest-green/20 hover:border-primary h-20 transition-colors" 
                         onclick="updateGallery('{{ $paket->foto2 ?? 'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=200&h=200&fit=crop' }}')">
                        <img src="{{ $paket->foto2 ?? 'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=200&h=200&fit=crop' }}" 
                             alt="Foto 2" class="w-full h-full object-cover hover:scale-110 transition-transform">
                    </div>
                    <div class="gallery-thumb rounded-lg overflow-hidden cursor-pointer border-2 border-forest-green/20 hover:border-primary h-20 transition-colors" 
                         onclick="updateGallery('{{ $paket->foto3 ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=200&h=200&fit=crop' }}')">
                        <img src="{{ $paket->foto3 ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=200&h=200&fit=crop' }}" 
                             alt="Foto 3" class="w-full h-full object-cover hover:scale-110 transition-transform">
                    </div>
                    <div class="gallery-thumb rounded-lg overflow-hidden cursor-pointer border-2 border-forest-green/20 hover:border-primary h-20 transition-colors" 
                         onclick="updateGallery('{{ $paket->foto4 ?? 'https://images.unsplash.com/photo-1511316695145-4992006ffddb?w=200&h=200&fit=crop' }}')">
                        <img src="{{ $paket->foto4 ?? 'https://images.unsplash.com/photo-1511316695145-4992006ffddb?w=200&h=200&fit=crop' }}" 
                             alt="Foto 4" class="w-full h-full object-cover hover:scale-110 transition-transform">
                    </div>
                </div>
            </div>

            <!-- Right: Booking Card & Title -->
            <div class="space-y-4">
                <!-- Category & Title -->
                <div>
                    <span class="inline-block text-primary font-black text-[10px] uppercase tracking-widest px-4 py-1 bg-primary/10 rounded-full mb-2">
                        {{ $paket->kategori }}
                    </span>
                    <h1 class="text-3xl md:text-4xl font-display font-black text-forest-green uppercase">
                        {{ $paket->nama }}
                    </h1>
                    <p class="text-xs text-forest-green/60 uppercase tracking-widest mt-1">{{ $paket->lokasi }}</p>
                </div>

                <!-- Booking Card -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-4">
                    <!-- Harga -->
                    <div>
                        <p class="text-xs text-forest-green/60 uppercase tracking-widest">Harga Dari</p>
                        <p class="text-3xl font-display font-black text-forest-green">
                            IDR {{ number_format($paket->harga / 1000000, 1) }}M
                        </p>
                        <p class="text-xs text-forest-green/50">/ pax</p>
                    </div>

                    <!-- Jadwal Selection -->
                    <div>
                        <p class="text-xs font-black text-forest-green uppercase tracking-widest mb-3">Pilih Jadwal</p>
                        <div class="space-y-2">
                            <!-- Single date for demo - dapat di-expand dengan data jadwal dari relasi -->
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                <input type="radio" name="jadwal" checked class="w-4 h-4 text-primary">
                                <span class="ml-3 flex-1">
                                    <span class="text-sm font-black text-forest-green">12 OKT</span>
                                    <span class="text-xs text-forest-green/50 ml-2">Ke-0174</span>
                                </span>
                                <span class="text-sm font-black text-forest-green">IDR 250k</span>
                            </label>
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <p class="text-xs font-black text-forest-green uppercase tracking-widest mb-2">Lokasi</p>
                        <p class="text-sm text-forest-green/70">{{ $paket->lokasi }}</p>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <p class="text-xs font-black text-forest-green uppercase tracking-widest mb-2">Jumlah Peserta</p>
                        <div class="flex items-center gap-3">
                            <button onclick="decreaseQty()" class="w-8 h-8 border border-gray-300 rounded flex items-center justify-center hover:bg-gray-100">−</button>
                            <input type="number" id="quantity" value="1" min="1" class="w-12 text-center font-black text-forest-green border border-gray-300 rounded py-1">
                            <button onclick="increaseQty()" class="w-8 h-8 border border-gray-300 rounded flex items-center justify-center hover:bg-gray-100">+</button>
                        </div>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Total -->
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-black text-forest-green">Total Pembayaran</span>
                        <p class="text-2xl font-display font-black text-forest-green">
                            IDR <span id="total">{{ number_format($paket->harga / 1000000, 1) }}M</span>
                        </p>
                    </div>

                    <!-- CTA Button -->
                    <button onclick="goToReservasi({{ $paket->paketId }})" class="w-full py-3 p-3 bg-forest-green hover:bg-forest-green/90 text-white font-black uppercase tracking-widest rounded text-center transition-all text-sm">
                        Reservasi Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tab Section -->
<section class="w-full bg-white border-b border-gray-200">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16">
        <div class="flex gap-8 overflow-x-auto">
            <button class="tab-btn py-4 font-black text-sm uppercase tracking-widest text-forest-green border-b-2 border-forest-green whitespace-nowrap transition-all active" data-tab="deskripsi">
                Deskripsi
            </button>
            <button class="tab-btn py-4 font-black text-sm uppercase tracking-widest text-forest-green/50 border-b-2 border-transparent hover:text-forest-green whitespace-nowrap transition-all" data-tab="fasilitas">
                Fasilitas
            </button>
            <button class="tab-btn py-4 font-black text-sm uppercase tracking-widest text-forest-green/50 border-b-2 border-transparent hover:text-forest-green whitespace-nowrap transition-all" data-tab="rancangan">
                Rancangan Perjalanan
            </button>
        </div>
    </div>
</section>

<!-- Tab Content -->
<section class="w-full bg-white py-12">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16">
        <!-- Deskripsi Tab -->
        <div id="deskripsi" class="tab-content space-y-8">
            <p class="text-forest-green/70 text-base leading-relaxed">
                {{ $paket->deskripsi }}
            </p>

            <!-- Durasi & Grup -->
            <div class="grid grid-cols-2 gap-8">
                <div class="bg-background-light p-6 rounded-lg">
                    <p class="text-xs font-black text-forest-green/60 uppercase tracking-widest mb-2">Durasi</p>
                    <p class="text-3xl font-display font-black text-forest-green">{{ $paket->durasi_hari }} HARI</p>
                </div>
                <div class="bg-background-light p-6 rounded-lg">
                    <p class="text-xs font-black text-forest-green/60 uppercase tracking-widest mb-2">Ukuran Grup</p>
                    <p class="text-2xl font-display font-black text-forest-green">MAKS. 15<br/>ORANG</p>
                </div>
            </div>

            <div class="prose prose-forest-green max-w-none">
                <p class="text-forest-green/70 text-base leading-relaxed">
                    Perjalanan kami dilengkapi dengan pemandu wisata berpengalaman yang akan memastikan setiap momen petualangan Anda berkesan. Nikmati keindahan alam yang menakjubkan dengan fasilitas lengkap dan layanan terbaik.
                </p>
            </div>
        </div>

        <!-- Fasilitas Tab -->
        <div id="fasilitas" class="tab-content space-y-6 hidden">
            <h3 class="text-2xl font-display font-black text-forest-green mb-6">Fasilitas Lengkap</h3>
            <ul class="space-y-3">
                @foreach(explode(',', $paket->fasilitas) as $fasilitas)
                    <li class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                        </svg>
                        <span class="text-forest-green/80">{{ trim($fasilitas) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Rancangan Tab -->
        <div id="rancangan" class="tab-content space-y-6 hidden">
            <!-- Include & Exclude -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h4 class="text-lg font-display font-black text-forest-green mb-4">✓ Termasuk</h4>
                    <ul class="space-y-2 text-sm text-forest-green/70">
                        @foreach(explode(',', $paket->include ?? '') as $item)
                            <li class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-primary rounded-full"></span>
                                {{ trim($item) }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-display font-black text-forest-green mb-4">✗ Tidak Termasuk</h4>
                    <ul class="space-y-2 text-sm text-forest-green/70">
                        @foreach(explode(',', $paket->exclude ?? '') as $item)
                            <li class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                {{ trim($item) }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Meeting Point -->
            <div class="p-6 bg-background-light rounded-lg border-l-4 border-primary">
                <h4 class="text-base font-display font-black text-forest-green mb-2">🚩 Titik Kumpul</h4>
                <p class="text-forest-green/70 text-sm">{{ $paket->meeting_point }}</p>
            </div>
        </div>
    </div>
</section>

<!-- What's Included Section -->
<section class="w-full bg-background-light py-16">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16">
        <h2 class="text-4xl font-display font-black text-forest-green uppercase mb-12">
            Apa Yang<br/>Akan Didapatkan
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <p class="text-xs font-black text-forest-green/60 uppercase tracking-widest mb-3">01</p>
                <h4 class="text-lg font-display font-black text-forest-green mb-3">Dokumentasi</h4>
                <p class="text-forest-green/70 text-sm">Kami menyediakan dokumentasi profesional selama perjalanan untuk mengabadikan momen-momen berharga Anda.</p>
            </div>
            <div>
                <p class="text-xs font-black text-forest-green/60 uppercase tracking-widest mb-3">02</p>
                <h4 class="text-lg font-display font-black text-forest-green mb-3">Logistik</h4>
                <p class="text-forest-green/70 text-sm">Transportasi lengkap, akomodasi nyaman, dan semua kebutuhan logistik Anda diurus dengan sempurna.</p>
            </div>
            <div>
                <p class="text-xs font-black text-forest-green/60 uppercase tracking-widest mb-3">03</p>
                <h4 class="text-lg font-display font-black text-forest-green mb-3">Panduan Ahli</h4>
                <p class="text-forest-green/70 text-sm">Pemandu wisata bersertifikat dan berpengalaman siap membimbing Anda sepanjang perjalanan.</p>
            </div>
        </div>
    </div>
</section>

<!-- Related Trips Section -->
<section class="w-full py-24 bg-background-light">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16">
        <h2 class="text-4xl font-display font-black text-forest-green uppercase mb-12">Paket Serupa</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            @foreach($paketSerupa ?? [] as $related)
                <x-trip-card 
                    id="{{ $related->paketId }}"
                    image="{{ $related->foto ?? 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=500&h=400&fit=crop' }}"
                    category="{{ strtoupper($related->kategori) }}"
                    title="{{ $related->nama }}"
                    description="{{ Str::limit($related->deskripsi, 80) }}"
                    price="IDR {{ number_format($related->harga / 1000000, 1) }}M"
                    rating="4.9"
                />
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="w-full py-20 bg-gradient-to-r from-forest-green to-primary text-white">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16 text-center space-y-8">
        <h2 class="text-4xl md:text-5xl font-display font-black uppercase">Jangan Ketinggalan Slot Terbatas Ini!</h2>
        <p class="text-lg text-white/80 max-w-2xl mx-auto">
            Tempat terbatas dan sering cepat habis. Amankan slot Anda sekarang dan mulai petualangan impian Anda.
        </p>
        <a href="{{ route('login') }}" class="inline-block px-16 py-4 bg-white text-forest-green font-black uppercase tracking-widest rounded-full hover:shadow-2xl transition-all">
            Pesan Sekarang
        </a>
    </div>
</section>

@push('scripts')
<script>
    // Gallery Functionality
    function updateGallery(imageSrc) {
        const mainImage = document.getElementById('gallery-main');
        mainImage.src = imageSrc.replace('w=200', 'w=1200').replace('h=200', 'h=600');
        
        document.querySelectorAll('.gallery-thumb').forEach(thumb => {
            thumb.classList.remove('border-primary');
            thumb.classList.add('border-forest-green/20');
        });
        
        event.target.closest('.gallery-thumb').classList.remove('border-forest-green/20');
        event.target.closest('.gallery-thumb').classList.add('border-primary');
    }

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        const thumbs = Array.from(document.querySelectorAll('.gallery-thumb'));
        const currentActive = document.querySelector('.gallery-thumb.border-primary');
        const currentIndex = thumbs.indexOf(currentActive);
        
        if (e.key === 'ArrowRight' && currentIndex < thumbs.length - 1) {
            thumbs[currentIndex + 1].click();
        } else if (e.key === 'ArrowLeft' && currentIndex > 0) {
            thumbs[currentIndex - 1].click();
        }
    });

    // Tab functionality
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const tabName = btn.dataset.tab;
            
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });
            
            // Show selected tab
            document.getElementById(tabName).classList.remove('hidden');
            
            // Update buttons
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('border-forest-green', 'text-forest-green');
                b.classList.add('border-transparent', 'text-forest-green/50');
            });
            btn.classList.add('border-forest-green', 'text-forest-green');
            btn.classList.remove('border-transparent', 'text-forest-green/50');
        });
    });

    // Quantity controls
    function increaseQty() {
        const qty = document.getElementById('quantity');
        qty.value = parseInt(qty.value) + 1;
        updateTotal();
    }

    function decreaseQty() {
        const qty = document.getElementById('quantity');
        if (parseInt(qty.value) > 1) {
            qty.value = parseInt(qty.value) - 1;
            updateTotal();
        }
    }

    function updateTotal() {
        const qty = parseInt(document.getElementById('quantity').value);
        const pricePerPerson = {{ $paket->harga / 1000000 }};
        const total = qty * pricePerPerson;
        document.getElementById('total').textContent = total.toFixed(1) + 'M';
    }

    // Listen to quantity input changes
    document.getElementById('quantity').addEventListener('change', updateTotal);

    // Navigate to reservasi page with paketId and quantity
    function goToReservasi(paketId) {
        @if(auth()->check())
            const quantity = document.getElementById('quantity').value;
            window.location.href = `{{ route('reservasi.create') }}?paketId=${paketId}&jml_peserta=${quantity}`;
        @else
            window.location.href = `{{ route('login') }}?redirect={{ url('/paket-trip/:id') }}`;
        @endif
    }
</script>
@endpush
@endsection
