<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - GateForestTrip')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important;}</style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
    @php
        $user = auth()->user();
    @endphp

    <div class="min-h-screen lg:flex">
        <div class="fixed inset-0 z-30 bg-slate-950/35 lg:hidden" x-cloak x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"></div>

        <aside class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full transform border-r border-gray-200 bg-white transition-transform duration-300 ease-out lg:sticky lg:top-0 lg:flex lg:h-screen lg:w-72 lg:flex-col lg:translate-x-0" :class="sidebarOpen ? 'translate-x-0 lg:translate-x-0' : '-translate-x-full lg:hidden'">
            <div class="flex items-center justify-between border-b border-gray-200 p-5 sm:p-6">
                <div class="flex items-center gap-3">
                    <img src="/images/logo/logo.png" alt="logo" class="w-12 h-12 object-contain">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400">Admin Panel</p>
                        <h1 class="mt-1 text-xl font-black uppercase text-gray-900">GateForestTrip</h1>
                    </div>
                </div>
                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 text-gray-500 transition hover:border-primary hover:text-primary lg:hidden" @click="sidebarOpen = false" aria-label="Tutup sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                        <path d="M18 6 6 18"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <nav class="flex flex-col gap-1 p-4 text-sm lg:flex-1 lg:space-y-1">
                <a href="{{ route('admin.dashboard') }}" @click="window.innerWidth < 1024 && (sidebarOpen = false)" class="flex w-full items-center rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.dashboard') ? 'bg-primary/10 text-primary font-black' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.paket-trip.index') }}" @click="window.innerWidth < 1024 && (sidebarOpen = false)" class="flex w-full items-center rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.paket-trip.*') ? 'bg-primary/10 text-primary font-black' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    Paket Trip
                </a>
                <a href="{{ route('admin.jadwal.index') }}" @click="window.innerWidth < 1024 && (sidebarOpen = false)" class="flex w-full items-center rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.jadwal.*') ? 'bg-primary/10 text-primary font-black' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    Jadwal
                </a>
                <a href="{{ route('admin.reservasi.index') }}" @click="window.innerWidth < 1024 && (sidebarOpen = false)" class="flex w-full items-center rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.reservasi.*') ? 'bg-primary/10 text-primary font-black' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    Reservasi
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="sticky top-0 z-30 border-b border-gray-200 bg-white">
                <div class="flex flex-col gap-4 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between gap-3 sm:justify-start">
                        <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-gray-200 text-gray-700 transition hover:border-primary hover:text-primary lg:hidden" @click="sidebarOpen = !sidebarOpen" aria-label="Buka tutup sidebar">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                <path d="M4 6h16"></path>
                                <path d="M4 12h16"></path>
                                <path d="M4 18h16"></path>
                            </svg>
                        </button>
                        <button type="button" class="hidden h-11 w-11 items-center justify-center rounded-2xl border border-gray-200 text-gray-700 transition hover:border-primary hover:text-primary lg:inline-flex" @click="sidebarOpen = !sidebarOpen" aria-label="Buka tutup sidebar">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                <path d="M4 6h16"></path>
                                <path d="M4 12h16"></path>
                                <path d="M4 18h16"></path>
                            </svg>
                        </button>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.28em] text-gray-400">{{ strtoupper($user?->role ?? 'admin') }}</p>
                            <h2 class="text-lg font-black uppercase text-gray-900">@yield('page-title', 'Dashboard Admin')</h2>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-3 sm:justify-end">
                        <div class="hidden sm:block text-right">
                            <p class="text-sm font-black text-gray-900">{{ $user?->nama }}</p>
                            <p class="text-xs text-gray-500">{{ $user?->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-full border border-gray-200 px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-gray-700 transition hover:border-primary hover:text-primary">Keluar</button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @if (session('success'))
                    <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>