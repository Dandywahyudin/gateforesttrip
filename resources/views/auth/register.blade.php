<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GateForestTrip - Daftar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white dark:bg-background-dark text-[#1c140d] dark:text-white antialiased">
    <div class="flex min-h-screen w-full flex-row overflow-hidden">
        <div class="hidden lg:flex lg:w-1/2 relative flex-col justify-between p-12 text-white bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=1200&fit=crop');">
            <div class="absolute inset-0 bg-black/40 bg-gradient-to-b from-black/50 via-transparent to-primary/30 mix-blend-multiply z-0"></div>

            <div class="relative z-10 flex flex-col h-full justify-between">
                <a href="/">
                <div class="flex items-center gap-3">
                    <img src="/images/logo/logo.png" alt="logo" class="w-12 h-12 object-contain">
                    <span class="text-2xl font-bold font-display uppercase text-white">GateForestTrip</span>
                </div>
                </a>

                <div class="max-w-md">
                    <h1 class="text-5xl font-black font-display leading-tight mb-6" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                        Mulai petualangan Anda dengan satu akun.
                    </h1>
                    <p class="text-lg font-medium text-white/90">
                        Buat akun untuk menjelajahi open trip, mengelola reservasi, dan memantau pembayaran dalam satu tempat.
                    </p>
                </div>

                <div class="text-sm text-white/70">
                    © 2026 GateForestTrip
                </div>
            </div>
        </div>

        <div class="flex w-full lg:w-1/2 flex-col justify-center items-center bg-white dark:bg-background-dark px-6 py-12 lg:px-20 overflow-y-auto">
            <div class="w-full max-w-[440px] flex flex-col gap-8">
                <a href="/" class="flex lg:hidden items-center gap-3 mb-4 self-center"><div class="flex lg:hidden items-center gap-3 mb-4 self-center">
                        <img src="/images/logo/logo.png" class="w-12 h-12 object-contain" srcset="" alt="logo">
                        <span class="text-xl font-bold font-display text-gray-900 dark:text-white uppercase">GateForestTrip</span>
                    </div>
                </a>

                <div class="text-left">
                    <h2 class="text-3xl font-black font-display text-gray-900 dark:text-white mb-2">Buat akun</h2>
                    <p class="text-gray-500 dark:text-gray-400">Isi data Anda untuk memulai.</p>
                </div>

                @if ($errors->any())
                    <div class="rounded-lg bg-red-50 dark:bg-red-900/20 p-4 border border-red-200 dark:border-red-800">
                        <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" class="flex flex-col gap-5">
                    @csrf

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-200" for="nama">Nama</label>
                        <div class="relative">
                            <input
                                class="w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-4 py-3.5 text-base text-gray-900 dark:text-white placeholder:text-gray-400 focus:border-primary focus:ring-1 focus:ring-primary transition-colors outline-none @error('nama') border-red-500 @enderror"
                                id="nama"
                                name="nama"
                                placeholder="Nama lengkap Anda"
                                type="text"
                                value="{{ old('nama') }}"
                                required
                                autofocus
                                autocomplete="name"
                            >
                            <span class="material-symbols-outlined absolute right-4 top-3.5 text-gray-400 pointer-events-none text-[20px]">badge</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-200" for="email">Email Address</label>
                        <div class="relative">
                            <input
                                class="w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-4 py-3.5 text-base text-gray-900 dark:text-white placeholder:text-gray-400 focus:border-primary focus:ring-1 focus:ring-primary transition-colors outline-none @error('email') border-red-500 @enderror"
                                id="email"
                                name="email"
                                placeholder="nama@contoh.com"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="username"
                            >
                            <span class="material-symbols-outlined absolute right-4 top-3.5 text-gray-400 pointer-events-none text-[20px]">mail</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-200" for="password">Password</label>
                        <div class="relative">
                            <input
                                class="w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-4 py-3.5 text-base text-gray-900 dark:text-white placeholder:text-gray-400 focus:border-primary focus:ring-1 focus:ring-primary transition-colors outline-none @error('password') border-red-500 @enderror"
                                id="password"
                                name="password"
                                placeholder="Buat kata sandi"
                                type="password"
                                required
                                autocomplete="new-password"
                            >
                            <span class="material-symbols-outlined absolute right-4 top-3.5 text-gray-400 text-[20px]">lock</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-200" for="password_confirmation">Confirm Password</label>
                        <div class="relative">
                            <input
                                class="w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-4 py-3.5 text-base text-gray-900 dark:text-white placeholder:text-gray-400 focus:border-primary focus:ring-1 focus:ring-primary transition-colors outline-none @error('password_confirmation') border-red-500 @enderror"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Ulangi kata sandi"
                                type="password"
                                required
                                autocomplete="new-password"
                            >
                            <span class="material-symbols-outlined absolute right-4 top-3.5 text-gray-400 text-[20px]">verified_user</span>
                        </div>
                    </div>

                    <button
                        class="group flex w-full items-center justify-center gap-2 rounded-xl bg-primary hover:bg-primary-dark px-4 py-3.5 text-base font-bold text-white shadow-lg shadow-primary/30 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-background-dark transition-all active:scale-[0.98]"
                        type="submit"
                    >
                        Daftar
                        <span class="material-symbols-outlined text-[20px] transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Sudah punya akun?
                        <a class="font-bold text-primary hover:text-primary-dark transition-colors" href="{{ route('login') }}">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
