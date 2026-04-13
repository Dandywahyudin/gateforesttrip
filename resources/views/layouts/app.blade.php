<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="GateForestTrip - Petualangan Alam Terpercaya">

    <title>@yield('title', 'GateForestTrip - Petualangan Alam Terpercaya')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-background-light dark:bg-background-dark font-body text-text-main-light dark:text-text-main-dark overflow-x-hidden">
    <x-header />

    <main class="flex flex-col w-full">
        @yield('content', $slot ?? '')
    </main>

    <x-footer />

    @stack('scripts')
</body>
</html>
