<header x-data="{ open: false }" x-on:keydown.escape.window="open = false" class="fixed top-0 z-[100] w-full bg-black/10 backdrop-blur-md border-b border-white/5 transition-all duration-300">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            {{-- <div class="size-10 text-primary flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                </svg>
            </div> --}}
            <img src="{{ asset('/images/logo/logo.png') }}" alt="GateForestTrip Logo" class="w-10 h-10 object-contain">
            <h3 class="text-2xl font-black font-display uppercase text-white">GateForestTrip</h3>
        </div>

        @php
            $user = auth()->user();
        @endphp

        <nav class="hidden md:flex items-center gap-10">
            <a class="text-xs font-bold text-white hover:text-primary transition-colors uppercase tracking-[0.2em]" href="/">Beranda</a>
            <a class="text-xs font-bold text-white/70 hover:text-primary transition-colors uppercase tracking-[0.2em]" href="{{ route('paket-trip.index') }}">PAKET TRIP</a>
            @auth
                @if($user->isAdmin())
                    <a class="text-xs font-bold text-white/70 hover:text-primary transition-colors uppercase tracking-[0.2em]" href="{{ route('admin.jadwal.index') }}">Jadwal</a>
                    <a class="text-xs font-bold text-white/70 hover:text-primary transition-colors uppercase tracking-[0.2em]" href="{{ route('admin.reservasi.index') }}">Reservasi</a>
                @endif
            @else
                <a class="text-xs font-bold text-white/70 hover:text-primary transition-colors uppercase tracking-[0.2em]" href="#hubungi-kami">Hubungi Kami</a>
            @endauth
        </nav>

        <div class="flex items-center gap-6">
            @auth
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="hidden sm:flex items-center gap-3 rounded-full bg-white/15 px-4 h-12 text-white border border-white/10 backdrop-blur-sm transition hover:bg-white/20">
                            <span class="flex flex-col items-start leading-none">
                                <span class="text-[10px] font-black uppercase tracking-[0.28em] text-white/60">{{ strtoupper($user->role) }}</span>
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
            <button type="button" @click="open = ! open" class="md:hidden p-2 text-white hover:text-primary transition-colors" :aria-expanded="open.toString()" aria-label="Toggle navigation">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                    <path x-show="!open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-transition class="md:hidden border-t border-white/10 bg-slate-950/95 backdrop-blur-md">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-16 py-5 space-y-5">
            <nav class="space-y-3">
                <a @click="open = false" class="block rounded-2xl px-4 py-3 text-xs font-black uppercase tracking-[0.2em] text-white hover:bg-white/5 hover:text-primary transition-colors" href="/">Beranda</a>
                <a @click="open = false" class="block rounded-2xl px-4 py-3 text-xs font-black uppercase tracking-[0.2em] text-white/80 hover:bg-white/5 hover:text-primary transition-colors" href="{{ route('paket-trip.index') }}">Paket Trip</a>

                @auth
                    @if($user->isAdmin())
                        <a @click="open = false" class="block rounded-2xl px-4 py-3 text-xs font-black uppercase tracking-[0.2em] text-white/80 hover:bg-white/5 hover:text-primary transition-colors" href="{{ route('admin.jadwal.index') }}">Jadwal</a>
                        <a @click="open = false" class="block rounded-2xl px-4 py-3 text-xs font-black uppercase tracking-[0.2em] text-white/80 hover:bg-white/5 hover:text-primary transition-colors" href="{{ route('admin.reservasi.index') }}">Reservasi</a>
                        <a @click="open = false" class="block rounded-2xl px-4 py-3 text-xs font-black uppercase tracking-[0.2em] text-white/80 hover:bg-white/5 hover:text-primary transition-colors" href="{{ route('admin.paket-trip.index') }}">Paket Trip</a>
                    @else
                        <a @click="open = false" class="block rounded-2xl px-4 py-3 text-xs font-black uppercase tracking-[0.2em] text-white/80 hover:bg-white/5 hover:text-primary transition-colors" href="{{ route('paket-trip.index') }}">Katalog</a>
                    @endif
                @else
                    <a @click="open = false" class="block rounded-2xl px-4 py-3 text-xs font-black uppercase tracking-[0.2em] text-white/80 hover:bg-white/5 hover:text-primary transition-colors" href="#hubungi-kami">Hubungi Kami</a>
                @endauth
            </nav>

            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                @auth
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.28em] text-white/50">{{ strtoupper($user->role) }}</p>
                            <p class="mt-1 text-sm font-bold text-white">{{ $user->nama }}</p>
                            <p class="mt-1 text-xs text-white/60">{{ $user->email }}</p>
                        </div>
                        <a @click="open = false" href="{{ route('profile.edit') }}" class="rounded-full border border-white/10 px-4 py-2 text-[10px] font-black uppercase tracking-[0.25em] text-white/80 hover:border-primary hover:text-primary transition-colors">Profil</a>
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
