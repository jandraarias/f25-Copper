<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ItinerEase') }}</title>

    <!-- Theme preload to prevent flash -->
    <script>
        (function() {
            const storedTheme = localStorage.getItem('theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (storedTheme === 'dark' || (!storedTheme && systemPrefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <!-- Favicons (light & dark) -->
    <link rel="icon" href="{{ asset('data/images/logos/itinerease-icon-light.svg') }}" type="image/svg+xml" media="(prefers-color-scheme: light)">
    <link rel="icon" href="{{ asset('data/images/logos/itinerease-icon-dark.svg') }}" type="image/svg+xml" media="(prefers-color-scheme: dark)">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-[Poppins] antialiased bg-sand dark:bg-sand-900 text-ink-900 dark:text-ink-200
             min-h-screen transition-colors duration-300 ease-in-out relative overflow-x-hidden">

    {{-- Background copper glow --}}
    <div class="absolute inset-0 bg-gradient-to-b from-copper/10 via-transparent to-transparent blur-3xl pointer-events-none -z-10"></div>

    {{-- Logo Section --}}
    <div class="flex flex-col items-center mt-10 mb-8">
        <a href="/" class="flex items-center justify-center group">
            {{-- Light Logo --}}
            <img src="{{ asset('data/images/logos/itinerease-logo-dark@2x.svg') }}" 
                 alt="ItinerEase Logo" 
                 class="h-10 block dark:hidden transition-transform duration-300 group-hover:scale-105">
            {{-- Dark Logo --}}
            <img src="{{ asset('data/images/logos/itinerease-logo-light.svg') }}" 
                 alt="ItinerEase Logo" 
                 class="h-10 hidden dark:block transition-transform duration-300 group-hover:scale-105">
        </a>

        <h1 class="mt-3 text-2xl font-semibold tracking-tight text-ink-900 dark:text-sand-100">
            {{ config('app.name', 'ItinerEase') }}
        </h1>
    </div>

    {{-- Slot (for login/register forms) --}}
    <main class="w-full max-w-md px-6 sm:px-8 mx-auto pb-20">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="pb-10 text-center text-sm text-ink-500 dark:text-ink-300/70">
        © {{ date('Y') }} ItinerEase. Designed by 
        <span class="text-copper dark:text-copper-light">Team Copper</span>.
    </footer>
</body>
</html>
