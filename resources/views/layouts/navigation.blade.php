    <nav x-data="{ open: false }" class="bg-slate-950 border-b border-white/10 text-white">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            @php
                $user = Auth::user();
                $isAdmin = $user?->isAdmin();
                $isWisatawan = $user?->isWisatawan();
            @endphp

            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('admin.*') || request()->routeIs('reservasi.riwayat')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if($isAdmin)
                        <x-nav-link :href="route('admin.cms')" :active="request()->routeIs('admin.cms')">
                            {{ __('CMS') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.paket-trip.index')" :active="request()->routeIs('admin.paket-trip.*')">
                            {{ __('Paket Trip') }}
                        </x-nav-link>
                    @elseif($isWisatawan)
                        <x-nav-link :href="route('paket-trip.index')" :active="request()->routeIs('paket-trip.*')">
                            {{ __('Katalog') }}
                        </x-nav-link>
                        <x-nav-link :href="route('reservasi.riwayat')" :active="request()->routeIs('reservasi.riwayat')">
                            {{ __('Riwayat') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 px-3 py-2 border border-white/10 text-sm leading-4 font-medium rounded-md text-white/80 bg-white/5 hover:text-white hover:bg-white/10 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex flex-col items-start leading-none">
                                <span class="text-[10px] font-black uppercase tracking-[0.28em] text-primary">{{ strtoupper($user?->role ?? '') }}</span>
                                <span>{{ $user?->nama }}</span>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('dashboard')">
                            {{ __('Dashboard') }}
                        </x-dropdown-link>

                        @if($isAdmin)
                            <x-dropdown-link :href="route('admin.cms')">
                                {{ __('CMS') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.paket-trip.index')">
                                {{ __('Paket Trip') }}
                            </x-dropdown-link>
                        @elseif($isWisatawan)
                            <x-dropdown-link :href="route('reservasi.riwayat')">
                                {{ __('Riwayat') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('paket-trip.index')">
                                {{ __('Katalog') }}
                            </x-dropdown-link>
                        @endif

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Keluar') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-white/80 hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-950/95 border-t border-white/10 backdrop-blur-md">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if($isAdmin)
                <x-responsive-nav-link :href="route('admin.cms')" :active="request()->routeIs('admin.cms')">
                    {{ __('CMS') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.paket-trip.index')" :active="request()->routeIs('admin.paket-trip.*')">
                    {{ __('Paket Trip') }}
                </x-responsive-nav-link>
            @elseif($isWisatawan)
                <x-responsive-nav-link :href="route('paket-trip.index')" :active="request()->routeIs('paket-trip.*')">
                    {{ __('Katalog') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reservasi.riwayat')" :active="request()->routeIs('reservasi.riwayat')">
                    {{ __('Riwayat') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-white/10">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ $user?->nama }}</div>
                <div class="font-medium text-sm text-white/60">{{ $user?->email }}</div>
                <div class="mt-2 inline-flex rounded-full bg-white/10 px-3 py-1 text-[10px] font-black uppercase tracking-[0.28em] text-white">{{ strtoupper($user?->role ?? '') }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Keluar') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
