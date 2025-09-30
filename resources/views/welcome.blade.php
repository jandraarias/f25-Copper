<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'ItinerEase') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Smooth scrolling -->
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC]" x-data="{ mobileMenu: false }">

    <!-- Sticky Navbar -->
    <header class="sticky top-0 z-50 w-full px-6 py-4 flex items-center shadow-sm bg-white dark:bg-[#161615]">
        <!-- Left side: brand + links -->
        <div class="flex items-center space-x-8">
            <!-- Brand -->
            <a href="#hero" class="text-3xl font-extrabold text-indigo-600 hover:text-indigo-800 flex items-center">
                ItinerEase
            </a>

            <!-- Nav links -->
            <nav class="hidden md:flex items-center space-x-6">
                <a href="#about" class="text-sm text-gray-700 dark:text-gray-300 hover:text-indigo-600">About</a>
                <a href="#features" class="text-sm text-gray-700 dark:text-gray-300 hover:text-indigo-600">Features</a>
                <a href="#team" class="text-sm text-gray-700 dark:text-gray-300 hover:text-indigo-600">Team</a>
                <a href="#contact" class="text-sm text-gray-700 dark:text-gray-300 hover:text-indigo-600">Contact</a>
            </nav>
        </div>

        <!-- Right side: auth links -->
        <div class="flex items-center space-x-4 ml-auto">
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
        </div>

        <!-- Mobile hamburger -->
        <button @click="mobileMenu = !mobileMenu" class="md:hidden ml-4 text-gray-700 dark:text-gray-300 focus:outline-none flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"/>
                <path x-show="mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </header>

    <!-- Mobile nav dropdown -->
    <div x-show="mobileMenu" x-transition class="md:hidden bg-white dark:bg-[#161615] shadow-lg px-6 py-4 space-y-4">
        <a @click="mobileMenu=false" href="#about" class="block text-gray-700 dark:text-gray-300 hover:text-indigo-600">About</a>
        <a @click="mobileMenu=false" href="#features" class="block text-gray-700 dark:text-gray-300 hover:text-indigo-600">Features</a>
        <a @click="mobileMenu=false" href="#team" class="block text-gray-700 dark:text-gray-300 hover:text-indigo-600">Team</a>
        <a @click="mobileMenu=false" href="#contact" class="block text-gray-700 dark:text-gray-300 hover:text-indigo-600">Contact</a>
        @if (Route::has('login'))
            @auth
                <a @click="mobileMenu=false" href="{{ url('/dashboard') }}" class="block text-indigo-600 font-semibold">Dashboard</a>
            @else
                <a @click="mobileMenu=false" href="{{ route('login') }}" class="block text-indigo-600">Log in</a>
                @if (Route::has('register'))
                    <a @click="mobileMenu=false" href="{{ route('register') }}" class="block text-indigo-600">Register</a>
                @endif
            @endauth
        @endif
    </div>

    <!-- Hero -->
    <section id="hero" class="flex flex-col items-center justify-center text-center py-20 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-4">
        <h2 class="text-4xl md:text-5xl font-bold mb-4">Plan and Share Your Trips with Ease</h2>
        <p class="text-lg max-w-2xl mb-6">ItinerEase helps travelers organize itineraries, manage preferences, and share adventures seamlessly.</p>
        <div class="space-x-4">
            <a href="{{ route('register') }}" class="px-6 py-3 bg-white text-indigo-600 font-semibold rounded-lg">Get Started</a>
            <a href="{{ route('login') }}" class="px-6 py-3 border border-white font-semibold rounded-lg">Log In</a>
        </div>
    </section>

    <!-- About -->
    <section id="about" class="py-16 px-6 lg:px-20 bg-[#FDFDFC] dark:bg-[#0a0a0a]">
        <h3 class="text-3xl font-bold text-center mb-8">About Us</h3>
        <p class="max-w-3xl mx-auto text-center text-lg text-gray-700 dark:text-gray-300">
            We built ItinerEase to make travel planning simple, collaborative, and fun. Whether you’re a casual traveler, a business professional, or a travel expert, our platform adapts to your needs.
        </p>
    </section>

    <!-- Features -->
    <section id="features" class="py-16 px-6 lg:px-20 bg-gray-50 dark:bg-[#161615]">
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
    <section id="team" class="py-16 px-6 lg:px-20 bg-[#FDFDFC] dark:bg-[#0a0a0a]" x-data="{ open: false }">
        <h3 class="text-3xl font-bold text-center mb-12">Meet the Team</h3>

        <div class="grid gap-12 md:grid-cols-2 text-center">
            <!-- First 2 members always visible -->
            <div>
                <img src="{{ asset('data/images/TeamHeadshots/Balemual Headshot.JPG') }}" alt="Balemual Ymamu" class="rounded-full mx-auto mb-4 w-40 h-40 object-cover">
                <h4 class="font-semibold">Balemual Ymamu</h4>
                <p class="text-sm text-gray-500">Software and Database Developer</p>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Balemual Ymamu is a senior at ODU majoring in Computer Science. He is eager to gain hands-on experience in tech field and look forward to working as a Software Developer after graduation.</p>
            </div>
            <div>
                <img src="{{ asset('data/images/TeamHeadshots/Crystal Headshot.PNG') }}" alt="Crystal Rivas" class="rounded-full mx-auto mb-4 w-40 h-40 object-cover">
                <h4 class="font-semibold">Crystal Rivas</h4>
                <p class="text-sm text-gray-500">Software and Database Developer</p>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Crystal Rivas is a second degree seeking student in Computer Science at ODU with a background in math education. Her expertise in problem solving drives the transition and aims to develop innovative and impactful solutions.</p>
            </div>

            <!-- Hidden members -->
            <template x-if="open">
                <div class="contents">
                    <div>
                        <img src="{{ asset('data/images/TeamHeadshots/Fredrick Headshot.JPG') }}" alt="Fredrick Terling" class="rounded-full mx-auto mb-4 w-40 h-40 object-cover">
                        <h4 class="font-semibold">Fredrick Terling</h4>
                        <p class="text-sm text-gray-500">Software Developer</p>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Fredrick Terling is a senior at ODU majoring in Computer Science. He is currently hoping to get his foot in the door for most tech job opportunities but dreams of being a Game Developer and ultimately a Game Producer.</p>
                    </div>
                    <div>
                        <img src="{{ asset('data/images/TeamHeadshots/Jandra Headshot.JPG') }}" alt="Jandra Arias" class="rounded-full mx-auto mb-4 w-40 h-40 object-cover">
                        <h4 class="font-semibold">Jandra D. Arias Tavarez</h4>
                        <p class="text-sm text-gray-500">Software and Database Developer</p>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Jandra D. Arias Tavarez is a second degree seeking student at ODU working on her bachelor in Computer Science. She aspires to work as a Software Developer after graduating.</p>
                    </div>
                    <div>
                        <img src="{{ asset('data/images/TeamHeadshots/William P. Headshot.JPG') }}" alt="William Poston" class="rounded-full mx-auto mb-4 w-40 h-40 object-cover">
                        <h4 class="font-semibold">William Poston</h4>
                        <p class="text-sm text-gray-500">Software Developer and Webmaster</p>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">William Poston is a senior at ODU majoring in computer science with a minor in data science. After school, he dreams of being an AI prompt engineer.</p>
                    </div>
                    <div>
                        <img src="{{ asset('data/images/TeamHeadshots/William M. Headshot.JPG') }}" alt="William Mbandi" class="rounded-full mx-auto mb-4 w-40 h-40 object-cover">
                        <h4 class="font-semibold">William Mbandi</h4>
                        <p class="text-sm text-gray-500">Software Developer</p>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">William Mbandi is a senior at ODU majoring in computer science with a minor in cyber security. Wants to work as a Software Developer after he graduates.</p>
                    </div>
                    <div>
                        <img src="{{ asset('data/images/TeamHeadshots/Stephen Headshot.JPG') }}" alt="Stephen Usselman" class="rounded-full mx-auto mb-4 w-40 h-40 object-cover">
                        <h4 class="font-semibold">Stephen Usselman</h4>
                        <p class="text-sm text-gray-500">Software Developer and Product Designer</p>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Stephen Usselman is a senior at ODU majoring in computer science. He is looking to pursue a career as a Software Developer after university.</p>
                    </div>
                    <div>
                        <img src="{{ asset('data/images/TeamHeadshots/Jacob Headshot.JPG') }}" alt="Jacob Cook" class="rounded-full mx-auto mb-4 w-40 h-40 object-cover">
                        <h4 class="font-semibold">Jacob Cook</h4>
                        <p class="text-sm text-gray-500">Mentor</p>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Jacob Cook is an ODU Computer Science alumnus, IT Instructor at the Southern Virginia Higher Education Center, and founder/CEO of Southside Tech Services. He has built a career spanning software development, general IT, and hands-on education, while also guiding students and businesses in adopting technology effectively. Jacob is passionate about mentoring, and he enjoys helping students bridge the gap between classroom learning and the real-world challenges of a career in technology.</p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Toggle button -->
        <div class="text-center mt-8">
            <button 
                @click="open = !open; $nextTick(() => document.getElementById('team').scrollIntoView({behavior: 'smooth'}))"
                class="px-6 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700"
                x-text="open ? 'Show Less' : 'Show More'">
            </button>
        </div>
    </section>

    <!-- Contact -->
    <section id="contact" class="py-16 px-6 lg:px-20 bg-gray-50 dark:bg-[#161615]">
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