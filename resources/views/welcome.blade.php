<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'ItinerEase') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC]">
    <!-- Navbar -->
    <header class="w-full px-6 py-4 flex justify-between items-center shadow-sm bg-white dark:bg-[#161615]">
        <h1 class="text-xl font-bold text-indigo-600">ItinerEase</h1>
        <nav class="space-x-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm px-4 py-2 rounded bg-indigo-600 text-white">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm px-4 py-2 rounded border border-indigo-600 text-indigo-600 hover:bg-indigo-50">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-sm px-4 py-2 rounded bg-indigo-600 text-white">Register</a>
                    @endif
                @endauth
            @endif
        </nav>
    </header>

    <!-- Hero -->
    <section class="flex flex-col items-center justify-center text-center py-20 bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
        <h2 class="text-5xl font-bold mb-4">Plan and Share Your Trips with Ease</h2>
        <p class="text-lg max-w-2xl mb-6">ItinerEase helps travelers organize itineraries, manage preferences, and share adventures seamlessly.</p>
        <div class="space-x-4">
            <a href="{{ route('register') }}" class="px-6 py-3 bg-white text-indigo-600 font-semibold rounded-lg">Get Started</a>
            <a href="{{ route('login') }}" class="px-6 py-3 border border-white font-semibold rounded-lg">Log In</a>
        </div>
    </section>

    <!-- About -->
    <section class="py-16 px-6 lg:px-20 bg-[#FDFDFC] dark:bg-[#0a0a0a]">
        <h3 class="text-3xl font-bold text-center mb-8">About Us</h3>
        <p class="max-w-3xl mx-auto text-center text-lg text-gray-700 dark:text-gray-300">
            We built ItinerEase to make travel planning simple, collaborative, and fun. Whether you’re a casual traveler, a business professional, or a travel expert, our platform adapts to your needs.
        </p>
    </section>

    <!-- Features -->
    <section class="py-16 px-6 lg:px-20 bg-gray-50 dark:bg-[#161615]">
        <h3 class="text-3xl font-bold text-center mb-12">Features</h3>
        <div class="grid gap-10 md:grid-cols-3 text-center">
            <div>
                <h4 class="font-semibold text-xl mb-2">Smart Itineraries</h4>
                <p class="text-gray-600 dark:text-gray-300">Organize your flights, hotels, and activities all in one place.</p>
            </div>
            <div>
                <h4 class="font-semibold text-xl mb-2">Preference Profiles</h4>
                <p class="text-gray-600 dark:text-gray-300">Save your travel style so your plans fit you perfectly every time.</p>
            </div>
            <div>
                <h4 class="font-semibold text-xl mb-2">Share & Export</h4>
                <p class="text-gray-600 dark:text-gray-300">Share trips via link or export a clean PDF itinerary instantly.</p>
            </div>
        </div>
    </section>

<!-- Meet the Team -->
<section class="py-16 px-6 lg:px-20 bg-[#FDFDFC] dark:bg-[#0a0a0a]" x-data="{ open: false }">
    <h3 class="text-3xl font-bold text-center mb-12">Meet the Team</h3>

    <!-- Team grid -->
    <div class="grid gap-12 md:grid-cols-2 text-center">
        <!-- First 2 rows (always visible) -->
        <div>
            <img src="{{ asset('data/images/TeamHeadshots/Balemual Headshot.JPG') }}" alt="Balemual Ymamu" class="rounded-full mx-auto mb-4 w-40 h-40 object-cover">
            <h4 class="font-semibold">Balemual Ymamu</h4>
            <p class="text-sm text-gray-500">Software and Database Developer</p>
        </div>
        <div>
            <img src="{{ asset('data/images/TeamHeadshots/Crystal Headshot.PNG') }}" alt="Crystal Rivas" class="rounded-full mx-auto mb-4 w-40 h-40 object-cover">
            <h4 class="font-semibold">Crystal Rivas</h4>
            <p class="text-sm text-gray-500">Software and Database Developer</p>
        </div>
        <div>
            <img src="{{ asset('data/images/TeamHeadshots/Fredrick Headshot.JPG') }}" alt="Fredrick Terling" class="rounded-full mx-auto mb-4 w-40 h-40 object-cover">
            <h4 class="font-semibold">Fredrick Terling</h4>
            <p class="text-sm text-gray-500">Software Developer</p>
        </div>
        <div>
            <img src="{{ asset('data/images/TeamHeadshots/Jandra Headshot.JPG') }}" alt="Jandra Arias" class="rounded-full mx-auto mb-4 w-40 h-40 object-cover">
            <h4 class="font-semibold">Jandra D. Arias Tavarez</h4>
            <p class="text-sm text-gray-500">Software and Database Developer</p>
        </div>

        <!-- Hidden rows (toggleable) -->
        <template x-if="open">
            <div class="contents">
                <div>
                    <img src="{{ asset('data/images/TeamHeadshots/William P. Headshot.JPG') }}" alt="William Poston" class="rounded-full mx-auto mb-4 w-40 h-40 object-cover">
                    <h4 class="font-semibold">William Poston</h4>
                    <p class="text-sm text-gray-500">Software Developer and Webmaster</p>
                </div>
                <div>
                    <img src="{{ asset('data/images/TeamHeadshots/William M. Headshot.JPG') }}" alt="William Mbandi" class="rounded-full mx-auto mb-4 w-40 h-40 object-cover">
                    <h4 class="font-semibold">William Mbandi</h4>
                    <p class="text-sm text-gray-500">Software Developer</p>
                </div>
                <div>
                    <img src="{{ asset('data/images/TeamHeadshots/Stephen Headshot.JPG') }}" alt="Stephen Usselman" class="rounded-full mx-auto mb-4 w-40 h-40 object-cover">
                    <h4 class="font-semibold">Stephen Usselman</h4>
                    <p class="text-sm text-gray-500">Software Developer and Product Designer</p>
                </div>
                <div>
                    <img src="{{ asset('data/images/TeamHeadshots/Jacob Headshot.JPG') }}" alt="Jacob Cook" class="rounded-full mx-auto mb-4 w-40 h-40 object-cover">
                    <h4 class="font-semibold">Jacob Cook</h4>
                    <p class="text-sm text-gray-500">Mentor</p>
                </div>
            </div>
        </template>
    </div>

    <!-- Toggle button -->
    <div class="text-center mt-8">
        <button 
            @click="open = !open"
            class="px-6 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700"
            x-text="open ? 'Show Less' : 'Show More'">
        </button>
    </div>
</section>

    <!-- Contact -->
    <section class="py-16 px-6 lg:px-20 bg-gray-50 dark:bg-[#161615]">
        <h3 class="text-3xl font-bold text-center mb-8">Contact Us</h3>
        <p class="text-center text-lg mb-8">We’d love to hear from you! Reach out with questions, feedback, or partnership inquiries.</p>
        <div class="text-center">
            <a href="mailto:contact@itinerEase.com" class="px-6 py-3 bg-indigo-600 text-white rounded-lg">Email Us</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-6 text-center text-sm bg-white dark:bg-[#161615]">
        <p>&copy; {{ date('Y') }} ItinerEase. All rights reserved.</p>
    </footer>
</body>
</html>
