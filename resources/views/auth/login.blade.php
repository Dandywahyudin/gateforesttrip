<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GateForestTrip - Masuk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white dark:bg-background-dark text-[#1c140d] dark:text-white antialiased">
    <div class="flex min-h-screen w-full flex-row overflow-hidden">
        <!-- Left Side: Hero Image & Inspiration -->
        <div class="hidden lg:flex lg:w-1/2 relative flex-col justify-between p-12 text-white bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=1200&fit=crop');">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/40 bg-gradient-to-b from-black/50 via-transparent to-primary/30 mix-blend-multiply z-0"></div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col h-full justify-between">
                <!-- Logo Area -->
                <a href="/">
                <div class="flex items-center gap-3">
                    <img src="/images/logo/logo.png" alt="logo" class="w-12 h-12 object-contain">
                    <span class="text-2xl font-bold font-display uppercase text-white">GateForestTrip</span>
                </div>
                </a>

                <!-- Quote Area -->
                <div class="max-w-md">
                    <h1 class="text-5xl font-black font-display leading-tight mb-6" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                        Temukan jalur petualangan terbaik.
                    </h1>
                    <p class="text-lg font-medium text-white/90">
                        Bergabunglah untuk menjelajahi trip alam terbaik dengan pengalaman yang lebih rapi dan praktis.
                    </p>
                </div>
                
                <!-- Footer/Copyright -->
                <div class="text-sm text-white/70">
                    © 2026 GateForestTrip.
                </div>
            </div>
        </div>
        
        <!-- Right Side: Login Form -->
        <div class="flex w-full lg:w-1/2 flex-col justify-center items-center bg-white dark:bg-background-dark px-6 py-12 lg:px-20 overflow-y-auto">
            <div class="w-full max-w-[440px] flex flex-col gap-8">
                <!-- Mobile Logo (Visible only on small screens) -->
                <a href="/">
                <div class="flex lg:hidden items-center gap-3 mb-4 self-center">
                    <img src="/images/logo/logo.png" alt="logo" class="w-12 h-12 object-contain">
                    <span class="text-xl font-bold font-display text-gray-900 dark:text-white uppercase">GateForestTrip</span>
                </div>
                </a>
                
                <!-- Header -->
                <div class="text-left">
                    <h2 class="text-3xl font-black font-display text-gray-900 dark:text-white mb-2">Selamat datang kembali</h2>
                    <p class="text-gray-500 dark:text-gray-400">Masukkan data Anda untuk masuk ke akun.</p>
                </div>
                
                <!-- Session Status -->
                @if ($errors->any())
                    <div class="rounded-lg bg-red-50 dark:bg-red-900/20 p-4 border border-red-200 dark:border-red-800">
                        <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- Form -->
                <form action="{{ route('login') }}" method="POST" class="flex flex-col gap-5">
                    @csrf
                    
                    <!-- Email Field -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-200" for="email">Email Address</label>
                        <div class="relative">
                            <input 
                                class="w-full rounded-xl border bg-gray-50 dark:bg-white/5 px-4 py-3.5 text-base text-gray-900 dark:text-white placeholder:text-gray-400 focus:border-primary focus:ring-1 focus:ring-primary transition-colors outline-none {{ $errors->has('email') ? 'border-red-500 dark:border-red-500' : 'border-gray-200 dark:border-white/10' }}"
                                id="email" 
                                name="email"
                                placeholder="nama@contoh.com" 
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                            >
                            <span class="material-symbols-outlined absolute right-4 top-3.5 text-gray-400 pointer-events-none text-[20px]">mail</span>
                        </div>
                    </div>
                    
                    <!-- Password Field -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-200" for="password">Password</label>
                        <div class="relative">
                            <input 
                                class="w-full rounded-xl border bg-gray-50 dark:bg-white/5 px-4 py-3.5 text-base text-gray-900 dark:text-white placeholder:text-gray-400 focus:border-primary focus:ring-1 focus:ring-primary transition-colors outline-none {{ $errors->has('password') ? 'border-red-500 dark:border-red-500' : 'border-gray-200 dark:border-white/10' }}"
                                id="password" 
                                name="password"
                                placeholder="Masukkan kata sandi" 
                                type="password"
                                required
                                autocomplete="current-password"
                            >
                            <span class="material-symbols-outlined absolute right-4 top-3.5 text-gray-400 text-[20px]">visibility_off</span>
                        </div>
                    </div>
                    
                    <!-- Forgot Password & Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input 
                                class="h-4 w-4 rounded border-gray-300 dark:border-white/10 text-primary focus:ring-primary/20 dark:bg-white/5"
                                name="remember"
                                type="checkbox"
                            >
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Ingat saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-sm font-bold text-primary hover:text-primary-dark transition-colors" href="{{ route('password.request') }}">Lupa kata sandi?</a>
                        @endif
                    </div>
                    
                    <!-- Login Button -->
                    <button 
                        class="group flex w-full items-center justify-center gap-2 rounded-xl bg-primary hover:bg-primary-dark px-4 py-3.5 text-base font-bold text-white shadow-lg shadow-primary/30 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-background-dark transition-all active:scale-[0.98]"
                        type="submit"
                    >
                        Masuk
                        <span class="material-symbols-outlined text-[20px] transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </button>
                </form>
                
                <!-- Divider -->
                <div class="relative flex items-center py-2">
                    <div class="flex-grow border-t border-gray-200 dark:border-gray-700"></div>
                    <span class="flex-shrink-0 px-4 text-xs font-medium text-gray-400 uppercase tracking-wider">Atau lanjut dengan</span>
                    <div class="flex-grow border-t border-gray-200 dark:border-gray-700"></div>
                </div>
                
                <!-- Social Login -->
                <div class="grid grid-cols-1 gap-4">
                    <a
                        href="{{ route('auth.google.redirect', ['context' => 'login']) }}"
                        class="flex items-center justify-center gap-3 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 px-4 py-3 text-sm font-semibold text-gray-700 dark:text-white shadow-sm hover:bg-gray-50 dark:hover:bg-white/10 transition-colors"
                    >
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"></path>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path>
                        </svg>
                        Masuk dengan Google
                    </a>
                </div>
                
                <!-- Sign Up Link -->
                <div class="text-center mt-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Belum punya akun? 
                        <a class="font-bold text-primary hover:text-primary-dark transition-colors" href="{{ route('register') }}">Daftar sekarang</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
