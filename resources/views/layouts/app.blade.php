<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ItinerEase') }}</title>

    <!-- Prevent flash: set theme before CSS loads -->
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

    <!-- Favicons (Light & Dark) -->
    <link rel="icon" href="{{ asset('data/images/logos/itinerease-icon-light.svg') }}" type="image/svg+xml" media="(prefers-color-scheme: light)">
    <link rel="icon" href="{{ asset('data/images/logos/itinerease-icon-dark.svg') }}" type="image/svg+xml" media="(prefers-color-scheme: dark)">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-[Poppins] bg-sand text-ink-900 dark:bg-sand-900 dark:text-ink-200 antialiased transition-colors duration-300 ease-in-out">
    <div class="min-h-screen flex flex-col">

        {{-- Navigation --}}
        {{-- NOTE: the navigation partial should include the updated logo logic shown below --}}
        @include('layouts.navigation')

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-green-50 dark:bg-forest-900/40 border border-green-300 dark:border-forest-900
                            text-green-800 dark:text-forest-100 px-4 py-3 rounded-xl shadow-soft">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-red-50 dark:bg-red-900/40 border border-red-300 dark:border-red-800
                            text-red-800 dark:text-red-100 px-4 py-3 rounded-xl shadow-soft">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- Header --}}
        @isset($header)
            <header class="bg-white dark:bg-sand-800 border-b border-sand-200 dark:border-ink-700 shadow-sm transition-colors duration-300">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- Main --}}
        <main class="flex-1 transition-colors duration-300">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="py-6 text-center text-sm text-ink-500 dark:text-ink-200/70 transition-colors duration-300">
            © {{ date('Y') }} ItinerEase. Designed by
            <span class="text-copper dark:text-copper-light">Team Copper</span>.
        </footer>
    </div>
</body>
</html>
