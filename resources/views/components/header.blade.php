@php
    $isLandingPage = request()->is('/') || request()->is('');
    $hubungiKamiUrl = url('/#hubungi-kami');

    $adminWhatsapp = \App\Models\User::query()
        ->where('role', 'admin')
        ->whereNotNull('no_hp')
        ->orderBy('userId')
        ->first();

    if ($adminWhatsapp?->no_hp) {
        $phoneNumber = preg_replace('/\D+/', '', (string) $adminWhatsapp->no_hp);

        if ($phoneNumber !== '') {
            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = '62' . substr($phoneNumber, 1);
            } elseif (str_starts_with($phoneNumber, '8')) {
                $phoneNumber = '62' . $phoneNumber;
            }

            $hubungiKamiUrl = 'https://wa.me/' . $phoneNumber . '?text=' . rawurlencode('Halo Admin GateForestTrip, saya ingin bertanya mengenai trip yang tersedia.');
        }
    }
@endphp

<header x-data="{ open: false }" x-on:keydown.escape.window="open = false" class="fixed top-0 z-[100] w-full transition-all duration-300 {{ $isLandingPage ? 'bg-black/10 backdrop-blur-md border-b border-white/5' : 'bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-sm' }}">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            {{-- <div class="size-10 text-primary flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                </svg>
            </div> --}}
            <a href="{{ url('/') }}">
                <img src="{{ asset('/images/logo/logo.webp') }}" alt="GateForestTrip Logo" class="w-12 h-12 object-contain">
            </a>
            <h3 class="text-2xl font-black font-display uppercase {{ $isLandingPage ? 'text-white' : 'text-gray-800' }}">GateForestTrip</h3>
        </div>

        @php
            $user = auth()->user();
        @endphp

        <nav class="hidden md:flex items-center gap-10">
            <a class="text-xs font-bold transition-colors uppercase tracking-[0.2em] {{ $isLandingPage ? 'text-white hover:text-primary' : 'text-gray-700 hover:text-primary' }}" href="{{ url('/#top') }}">Beranda</a>
            <a class="text-xs font-bold transition-colors uppercase tracking-[0.2em] {{ $isLandingPage ? 'text-white/70 hover:text-primary' : 'text-gray-600 hover:text-primary' }}" href="{{ route('paket-trip.index') }}">Paket Trip</a>
            <a class="text-xs font-bold transition-colors uppercase tracking-[0.2em] {{ $isLandingPage ? 'text-white/70 hover:text-primary' : 'text-gray-600 hover:text-primary' }}" href="{{ $hubungiKamiUrl }}" target="_blank" rel="noopener noreferrer">Hubungi Kami</a>
        </nav>

        <div class="flex items-center gap-6">
            @auth
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="hidden sm:flex items-center gap-3 rounded-full px-4 h-12 border backdrop-blur-sm transition {{ $isLandingPage ? 'bg-white/15 text-white border-white/10 hover:bg-white/20' : 'bg-gray-50 text-gray-900 border-gray-200 hover:bg-gray-100' }}">
                            <span class="flex flex-col items-start leading-none">
                                <span class="text-[10px] font-black uppercase tracking-[0.28em] {{ $isLandingPage ? 'text-white/60' : 'text-gray-500' }}">{{ strtoupper($user->role) }}</span>
                                <span class="text-sm font-bold">{{ $user->nama }}</span>
                            </span>
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if($user->isAdmin())
                            <x-dropdown-link :href="route('admin.jadwal.index')">
                                Jadwal
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.paket-trip.index')">
                                Paket Trip
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.reservasi.index')">
                                Reservasi
                            </x-dropdown-link>
                        @endif

                        <x-dropdown-link :href="route('reservasi.riwayat')">
                            Riwayat
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('profile.edit')">
                            Profil
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                Keluar
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @else
                <a href="{{ route('login') }}"
                    class="hidden sm:flex px-8 h-10 items-center justify-center rounded-full bg-primary hover:bg-orange-700 text-white text-xs font-black uppercase tracking-widest transition-all shadow-xl shadow-primary/20">
                        Masuk
                    </a>
            @endauth
            <button type="button" @click="open = ! open" class="md:hidden p-2 text-white transition-colors hover:text-white/80" :aria-expanded="open.toString()" aria-label="Toggle navigation">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                    <path x-show="!open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

        <div x-show="open" x-transition class="md:hidden border-t border-gray-200 bg-white/95 backdrop-blur-md">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-16 py-5 space-y-5">
            <nav class="space-y-3">
            <a @click="open = false" class="block rounded-2xl px-4 py-3 text-xs font-black uppercase tracking-[0.2em] text-gray-900 transition-colors hover:bg-gray-100 hover:text-primary" href="{{ url('/#top') }}">Beranda</a>
            <a @click="open = false" class="block rounded-2xl px-4 py-3 text-xs font-black uppercase tracking-[0.2em] text-gray-700 transition-colors hover:bg-gray-100 hover:text-primary" href="{{ route('paket-trip.index') }}">Paket Trip</a>
            <a @click="open = false" class="block rounded-2xl px-4 py-3 text-xs font-black uppercase tracking-[0.2em] text-gray-700 transition-colors hover:bg-gray-100 hover:text-primary" href="{{ $hubungiKamiUrl }}" target="_blank" rel="noopener noreferrer">Hubungi Kami</a>

                @auth
                    @if($user->isAdmin())
                        <a @click="open = false" class="block rounded-2xl px-4 py-3 text-xs font-black uppercase tracking-[0.2em] text-gray-700 transition-colors hover:bg-gray-100 hover:text-primary" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    @endif
                @else
                    <a @click="open = false" class="block rounded-2xl px-4 py-3 text-xs font-black uppercase tracking-[0.2em] text-gray-700 transition-colors hover:bg-gray-100 hover:text-primary" href="{{ $hubungiKamiUrl }}" target="_blank" rel="noopener noreferrer">Hubungi Kami</a>
                @endauth
            </nav>

            <div class="rounded-3xl border border-gray-200 bg-gray-50 p-4">
                @auth
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.28em] text-gray-500">{{ strtoupper($user->role) }}</p>
                            <p class="mt-1 text-sm font-bold text-gray-900">{{ $user->nama }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                        <div class="flex flex-col gap-2">
                            @if($user->isAdmin())
                                <a @click="open = false" href="{{ route('admin.dashboard') }}" class="rounded-full border border-gray-200 px-4 py-2 text-[10px] font-black uppercase tracking-[0.25em] text-gray-700 transition-colors hover:border-primary hover:text-primary">Dashboard</a>
                            @endif
                            <a @click="open = false" href="{{ route('reservasi.riwayat') }}" class="rounded-full border border-gray-200 px-4 py-2 text-[10px] font-black uppercase tracking-[0.25em] text-gray-700 transition-colors hover:border-primary hover:text-primary">Riwayat</a>
                            <a @click="open = false" href="{{ route('profile.edit') }}" class="rounded-full border border-gray-200 px-4 py-2 text-[10px] font-black uppercase tracking-[0.25em] text-gray-700 transition-colors hover:border-primary hover:text-primary">Profil</a>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full rounded-2xl bg-primary px-4 py-3 text-xs font-black uppercase tracking-[0.25em] text-white transition hover:bg-orange-700">Keluar</button>
                    </form>
                @else
                    <a @click="open = false" href="{{ route('login') }}" class="flex w-full items-center justify-center rounded-2xl bg-primary px-4 py-3 text-xs font-black uppercase tracking-[0.25em] text-white transition hover:bg-orange-700">Masuk</a>
                @endauth
            </div>
        </div>
    </div>
</header>
