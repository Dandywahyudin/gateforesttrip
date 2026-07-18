@extends('layouts.app')

@section('title', $paket->nama . ' - GateForestTrip')

@section('content')
@php
    $galleryImages = array_values(array_filter([
        $paket->foto_url,
        $paket->foto2_url,
        $paket->foto3_url,
        $paket->foto4_url,
    ]));
@endphp
<section class="w-full pt-24 pb-10 bg-white">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16">
        <div class="mb-6">
            <x-breadcrumbs :items="[
                ['label' => 'Home', 'url' => url('/')],
                ['label' => 'Trip', 'url' => route('paket-trip.index')],
                ['label' => $paket->nama, 'url' => null],
            ]" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1.25fr_0.85fr] gap-8 items-start">
            <div class="space-y-4">
                <div class="relative overflow-hidden rounded-[32px] border border-white/60 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.12)]">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-black/5 to-transparent z-10"></div>
                    <div class="absolute left-5 top-5 z-20 flex flex-wrap gap-2">
                        <span class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-[10px] font-black uppercase tracking-[0.28em] text-forest-green backdrop-blur-sm">
                            {{ $paket->kategori_label ?? 'Paket Aktif' }}
                        </span>
                        <span class="inline-flex items-center rounded-full bg-primary/90 px-3 py-1 text-[10px] font-black uppercase tracking-[0.28em] text-white backdrop-blur-sm">
                            Open Trip
                        </span>
                    </div>

                    <div class="aspect-[16/11] w-full">
                        @if(count($galleryImages))
                            <img id="gallery-main" src="{{ $galleryImages[0] }}" alt="{{ $paket->nama }}" class="h-full w-full object-cover transition-transform duration-700 hover:scale-105">
                        @else
                            <div id="gallery-main" class="flex h-full w-full items-center justify-center bg-white px-6 text-center">
                                <div class="space-y-3 max-w-md">
                                    <p class="text-[10px] font-black uppercase tracking-[0.35em] text-forest-green/50">Foto belum tersedia</p>
                                    <h2 class="text-2xl md:text-4xl font-display font-black uppercase text-forest-green leading-tight">{{ $paket->nama }}</h2>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="absolute inset-x-0 bottom-0 z-20 p-5 md:p-7">
                        <div class="max-w-2xl space-y-2 text-white">
                            <p class="text-[10px] font-black uppercase tracking-[0.35em] text-white/75">Paket Trip Pilihan</p>
                            <h1 class="text-3xl md:text-5xl font-display font-black uppercase leading-tight drop-shadow-md">
                                {{ $paket->nama }}
                            </h1>
                            <p class="text-sm md:text-base text-white/85 tracking-wide">{{ $paket->lokasi }}</p>
                        </div>
                    </div>
                </div>

                @if(count($galleryImages))
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach($galleryImages as $index => $image)
                            <button type="button" class="gallery-thumb overflow-hidden rounded-2xl border {{ $loop->first ? 'border-primary ring-2 ring-primary/10' : 'border-forest-green/10 ring-0 hover:border-primary/60' }} aspect-square transition" onclick="updateGallery(this, @js($image))">
                                <img src="{{ $image }}" alt="Foto {{ $index + 1 }}" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-[24px] border border-dashed border-forest-green/15 bg-white px-6 py-8 text-center text-sm text-forest-green/60">
                        Belum ada foto paket yang diunggah.
                    </div>
                @endif
            </div>

            <div class="lg:sticky lg:top-28 space-y-4">
                <div class="rounded-[28px] border border-white/70 bg-white/90 p-6 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur-md space-y-6">
                    <div class="space-y-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center rounded-full text-[15px] font-black uppercase tracking-[0.12em] text-forest-green">Detail Paket</span>
                        </div>

                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.28em] text-black">Harga mulai</p>
                            <p class="mt-2 text-4xl font-display font-black text-black">Rp {{ number_format($paket->harga, 0, ',', '.') }}</p>
                            <p class="mt-1 text-xs black">Per orang</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-lg bg-background-light p-4">
                                <p class="text-[10px] font-black uppercase tracking-[0.25em] text-forest-green/40 mb-2">Lokasi</p>
                                <p class="text-sm font-bold text-forest-green leading-snug">{{ $paket->lokasi }}</p>
                            </div>
                            <div class="rounded-lg bg-background-light p-4">
                                <p class="text-[10px] font-black uppercase tracking-[0.25em] text-forest-green/40 mb-2">Durasi</p>
                                <p class="text-sm font-bold text-forest-green leading-snug">{{ $paket->durasi_hari }} Hari</p>
                            </div>
                        </div>

                        <div class="rounded-lg border border-dashed border-forest-green/15 bg-white p-4">
                            <p class="text-[10px] font-black uppercase tracking-[0.25em] text-forest-green/40 mb-2">Meeting Point</p>
                            <p class="text-sm leading-relaxed text-forest-green/70">{{ $paket->meeting_point ?? '-' }}</p>
                        </div>
                    </div>

                    @auth
                        @if(auth()->user()->isWisatawan())
                            <a href="{{ route('reservasi.jadwal', $paket) }}" class="group flex w-full items-center justify-center gap-3 rounded-2xl bg-forest-green px-5 py-4 text-sm font-black uppercase tracking-[0.28em] text-white transition hover:-translate-y-0.5 hover:bg-forest-green/90 hover:shadow-lg hover:shadow-forest-green/20">
                                <span>Reservasi Sekarang</span>
                                <span class="transition group-hover:translate-x-1">→</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="group flex w-full items-center justify-center gap-3 rounded-2xl bg-forest-green px-5 py-4 text-sm font-black uppercase tracking-[0.28em] text-white transition hover:-translate-y-0.5 hover:bg-forest-green/90 hover:shadow-lg hover:shadow-forest-green/20">
                                <span>Login untuk Reservasi</span>
                                <span class="transition group-hover:translate-x-1">→</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="group flex w-full items-center justify-center gap-3 rounded-2xl bg-forest-green px-5 py-4 text-sm font-black uppercase tracking-[0.28em] text-white transition hover:-translate-y-0.5 hover:bg-forest-green/90 hover:shadow-lg hover:shadow-forest-green/20">
                            <span>Login untuk Reservasi</span>
                            <span class="transition group-hover:translate-x-1">→</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tab Section -->
<section class="w-full bg-white/95 border-y border-forest-green/10 backdrop-blur-sm">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16">
        <div class="flex gap-3 overflow-x-auto py-3 sm:py-4">
            {{-- <button class="tab-btn shrink-0 rounded-full border border-forest-green/10 bg-forest-green px-5 py-3 text-xs font-black uppercase tracking-[0.25em] text-white shadow-sm transition-all active" data-tab="deskripsi">
                Deskripsi
            </button> --}}
            <button class="tab-btn shrink-0 rounded-full border border-forest-green/10 bg-white px-5 py-3 text-xs font-black uppercase tracking-[0.25em] text-forest-green/60 shadow-sm transition-all hover:border-primary/30 hover:text-forest-green" data-tab="deskripsi">
                Deskripsi
            </button>
            <button class="tab-btn shrink-0 rounded-full border border-forest-green/10 bg-white px-5 py-3 text-xs font-black uppercase tracking-[0.25em] text-forest-green/60 shadow-sm transition-all hover:border-primary/30 hover:text-forest-green" data-tab="fasilitas">
                Fasilitas
            </button>
            <button class="tab-btn shrink-0 rounded-full border border-forest-green/10 bg-white px-5 py-3 text-xs font-black uppercase tracking-[0.25em] text-forest-green/60 shadow-sm transition-all hover:border-primary/30 hover:text-forest-green" data-tab="rancangan">
                Rancangan Perjalanan
            </button>
        </div>
    </div>
</section>

<!-- Tab Content -->
<section class="w-full bg-[#fbfcfa] py-14 lg:py-20">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16">
        <!-- Deskripsi Tab -->
        <div id="deskripsi" class="tab-content space-y-8">
            <div class="grid grid-cols-1 lg:grid-cols-[1.25fr_0.75fr] gap-8 items-start">
                <div class="space-y-6">
                    <div class="space-y-3 max-w-3xl">
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-primary">Tentang Paket</p>
                        <h2 class="text-3xl md:text-4xl font-display font-black text-forest-green uppercase leading-tight">Perjalanan yang disusun untuk pengalaman yang rapi dan berkesan</h2>
                        <p class="text-forest-green/70 text-base leading-relaxed">{{ $paket->deskripsi }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="rounded-3xl border border-forest-green/10 bg-white p-5 shadow-sm">
                            <p class="text-[10px] font-black uppercase tracking-[0.25em] text-forest-green/40 mb-2">Durasi</p>
                            <p class="text-3xl font-display font-black text-forest-green">{{ $paket->durasi_hari }}</p>
                            <p class="mt-1 text-sm text-forest-green/60">Hari perjalanan</p>
                        </div>
                        <div class="rounded-3xl border border-forest-green/10 bg-white p-5 shadow-sm">
                            <p class="text-[10px] font-black uppercase tracking-[0.25em] text-forest-green/40 mb-2">Kategori</p>
                            <p class="text-3xl font-display font-black text-forest-green">{{ strtoupper($paket->kategori_label ?? 'PAKET AKTIF') }}</p>
                            <p class="mt-1 text-sm text-forest-green/60">Konsep perjalanan</p>
                        </div>
                        <div class="rounded-3xl border border-forest-green/10 bg-white p-5 shadow-sm">
                            <p class="text-[10px] font-black uppercase tracking-[0.25em] text-forest-green/40 mb-2">Lokasi</p>
                            <p class="text-3xl font-display font-black text-forest-green">{{ Str::limit($paket->lokasi, 16) }}</p>
                            <p class="mt-1 text-sm text-forest-green/60">Destinasi utama</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-[28px] border border-forest-green/10 bg-white p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-primary mb-4">Highlight</p>
                    <div class="space-y-4">
                        <div class="rounded-lg bg-background-light p-4">
                            <p class="text-xs font-black uppercase tracking-[0.25em] text-black/70 mb-1">Fasilitas Utama</p>
                            <ul class="space-y-2 text-sm leading-relaxed text-forest-green/70">
                                @foreach($paket->fasilitas_items as $item)
                                    <li class="flex items-start gap-3">
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="rounded-lg bg-background-light p-4">
                            <p class="text-xs font-black uppercase tracking-[0.25em] text-black/70 mb-1">Meeting Point</p>
                            <p class="text-sm leading-relaxed text-forest-green/70">{{ $paket->meeting_point ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fasilitas Tab -->
        <div id="fasilitas" class="tab-content space-y-6 hidden">
            <div class="flex items-end justify-between gap-4 mb-4">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-primary mb-2">Fasilitas</p>
                    <h3 class="text-3xl font-display font-black text-forest-green uppercase">Semua yang termasuk di paket ini</h3>
                </div>
            </div>

            <ul class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($paket->fasilitas_items as $fasilitas)
                    <li class="flex items-start gap-3 rounded-2xl border border-forest-green/10 bg-white p-4 shadow-sm">
                        <span class="text-forest-green/80 leading-relaxed">{{ trim($fasilitas) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Rancangan Tab -->
        <div id="rancangan" class="tab-content space-y-8 hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="rounded-[28px] border border-forest-green/10 bg-white p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-primary mb-3">Termasuk</p>
                    <h4 class="text-2xl font-display font-black text-forest-green uppercase mb-5">Apa yang didapat</h4>
                    <ul class="space-y-3 text-sm text-forest-green/70">
                        @foreach($paket->include_items as $item)
                            <li class="flex items-start gap-3 rounded-2xl bg-background-light p-4">
                                
                                <span class="leading-relaxed">{{ trim($item) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="rounded-[28px] border border-forest-green/10 bg-white p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-primary mb-3">Tidak Termasuk</p>
                    <h4 class="text-2xl font-display font-black text-forest-green uppercase mb-5">Pengecualian paket</h4>
                    <ul class="space-y-3 text-sm text-forest-green/70">
                        @foreach($paket->exclude_items as $item)
                            <li class="flex items-start gap-3 rounded-2xl bg-background-light p-4">
                                <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-red-100 text-red-600 shrink-0">
                                    <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                </span>
                                <span class="leading-relaxed">{{ trim($item) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="rounded-[28px] border border-primary/15 bg-gradient-to-r from-primary/8 to-white p-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-primary mb-2">Meeting Point</p>
                        <h4 class="text-2xl font-display font-black text-forest-green uppercase">Titik Kumpul</h4>
                    </div>
                    <p class="text-forest-green/70 text-sm leading-relaxed max-w-2xl">{{ $paket->meeting_point }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Gallery Functionality
    function updateGallery(button, imageSrc) {
        const mainImage = document.getElementById('gallery-main');
        if (!mainImage || !imageSrc) {
            return;
        }

        if (mainImage.tagName === 'IMG') {
            mainImage.src = imageSrc;
        }
        
        document.querySelectorAll('.gallery-thumb').forEach(thumb => {
            thumb.classList.remove('border-primary');
            thumb.classList.add('border-forest-green/20');
        });

        if (button) {
            button.classList.remove('border-forest-green/20');
            button.classList.add('border-primary');
        }
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

</script>
@endpush
@endsection
