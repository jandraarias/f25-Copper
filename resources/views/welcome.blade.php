{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'ItinerEase') }}</title>

    <!-- Favicons -->
    <link rel="icon" href="{{ asset('data/images/logos/itinerease-icon-light.svg') }}" type="image/svg+xml" media="(prefers-color-scheme: light)">
    <link rel="icon" href="{{ asset('data/images/logos/itinerease-icon-dark.svg') }}" type="image/svg+xml" media="(prefers-color-scheme: dark)">

    <!-- Theme preload -->
    <script>
        (function () {
            const stored = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (stored === 'dark' || (!stored && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Smooth scroll -->
    <style> html { scroll-behavior: smooth; } </style>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-[Poppins] bg-sand text-ink-900 dark:bg-ink-900 dark:text-sand-100 antialiased transition-colors duration-500 ease-in-out"
    x-data="{ darkMode: localStorage.getItem('theme') === 'dark', mobileMenu: false }"
    x-init="if (darkMode) document.documentElement.classList.add('dark')">

    <!-- Navbar -->
    <header class="sticky top-0 z-50 w-full bg-sand dark:bg-sand-800 border-b border-sand-200 dark:border-ink-700 shadow-sm transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            <!-- Logo -->
            <a href="#hero" class="flex items-center">
                <img src="{{ asset('data/images/logos/itinerease-logo-dark@2x.svg') }}"
                     alt="ItinerEase Logo"
                     class="h-9 block dark:hidden hover:scale-105 transition-transform duration-300">
                <img src="{{ asset('data/images/logos/itinerease-logo-light.svg') }}"
                     alt="ItinerEase Logo"
                     class="h-9 hidden dark:block hover:scale-105 transition-transform duration-300">
            </a>

            <!-- Desktop Nav -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="#about" class="text-sm font-semibold text-ink-900 dark:text-sand-100 hover:text-copper-dark dark:hover:text-copper-light transition-colors">
                    About
                </a>
                <a href="#features" class="text-sm font-semibold text-ink-900 dark:text-sand-100 hover:text-copper-dark dark:hover:text-copper-light transition-colors">
                    Features
                </a>
                <a href="#team" class="text-sm font-semibold text-ink-900 dark:text-sand-100 hover:text-copper-dark dark:hover:text-copper-light transition-colors">
                    Team
                </a>
                <a href="#contact" class="text-sm font-semibold text-ink-900 dark:text-sand-100 hover:text-copper-dark dark:hover:text-copper-light transition-colors">
                    Contact
                </a>
            </nav>

            <!-- Right Side -->
            <div class="flex items-center space-x-4">

                <!-- Theme Toggle -->
                <button @click="
                    darkMode = !darkMode;
                    if (darkMode) {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    }"
                    class="p-2 rounded-full border border-sand-300 dark:border-ink-600 text-ink-900 dark:text-sand-100
                           hover:bg-sand-200 dark:hover:bg-ink-800 transition-all duration-300"
                    aria-label="Toggle theme">

                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5 text-copper-dark" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 3v1m0 16v1m9-9h1M4 12H3m15.364-7.364l.707.707M6.343 17.657l-.707.707m0-12.02l.707.707M18.364 17.657l.707.707" />
                    </svg>

                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5 text-copper-light" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
                    </svg>
                </button>

                <!-- Auth Buttons -->
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="px-4 py-2 rounded-full bg-gradient-copper text-white font-semibold shadow-soft
                                  hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-4 py-2 rounded-full border border-ink-900 dark:border-sand-100 text-ink-900 dark:text-sand-100 font-semibold
                                  hover:bg-ink-900 hover:text-sand-100 dark:hover:bg-sand-100 dark:hover:text-ink-900
                                  transition-all duration-200 ease-out">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-4 py-2 rounded-full bg-gradient-copper text-white font-semibold shadow-soft
                                      hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section id="hero"
        class="relative flex flex-col items-center justify-center text-center py-28 px-6 
               bg-gradient-to-r from-copper-dark to-copper-900 dark:from-copper-900 dark:to-copper-dark
               text-white overflow-hidden">

        <div x-data x-intersect="$el.classList.add('opacity-100','translate-y-0')"
             class="opacity-0 translate-y-4 transition-all duration-700 ease-out max-w-3xl mx-auto">

            <h1 class="text-4xl md:text-6xl font-extrabold mb-6 leading-tight">
                Plan and Share Your Trips with Ease
            </h1>

            <p class="text-lg md:text-xl max-w-2xl mx-auto text-white/95 mb-10">
                ItinerEase helps travelers organize itineraries, manage preferences, and share adventures seamlessly.
            </p>

            <div class="flex flex-wrap justify-center gap-4">

                <a href="{{ route('register') }}"
                   class="px-6 py-3 rounded-full bg-white text-ink-900 font-semibold 
                          hover:bg-sand-100 transition duration-200 ease-out shadow-soft">
                    Get Started
                </a>

                <a href="{{ route('login') }}"
                   class="px-6 py-3 rounded-full border border-white text-white font-semibold
                          hover:bg-white/20 transition duration-200 ease-out">
                    Log In
                </a>

            </div>
        </div>
    </section>

    <!-- About -->
    <section id="about"
        class="py-20 px-6 lg:px-20 bg-sand dark:bg-sand-900 text-center transition-colors duration-500">

        <div x-data x-intersect="$el.classList.add('opacity-100','translate-y-0')"
             class="opacity-0 translate-y-6 transition-all duration-700 ease-out">

            <h3 class="text-3xl font-extrabold mb-6 text-ink-900 dark:text-sand-100">
                About ItinerEase
            </h3>

            <p class="max-w-3xl mx-auto text-lg text-ink-900 dark:text-sand-100 leading-relaxed">
                ItinerEase was built to make travel planning effortless, collaborative,
                and beautifully organized. Whether you're a solo traveler or part of a group,
                our tools help you stay coordinated and inspired.
            </p>
        </div>
    </section>


    <!-- Features -->
    <section id="features"
        class="py-24 px-6 lg:px-20 bg-sand-100 dark:bg-sand-800 transition-colors duration-500">

        <div x-data x-intersect="$el.classList.add('opacity-100','translate-y-0')"
             class="opacity-0 translate-y-6 transition-all duration-700 ease-out text-center">

            <h3 class="text-3xl font-extrabold mb-12 text-ink-900 dark:text-sand-100">
                Features
            </h3>

            <div class="grid md:grid-cols-3 gap-10">

                <!-- Feature 1 -->
                <div class="bg-white dark:bg-sand-900 rounded-3xl p-8 shadow-soft
                            hover:shadow-glow hover:scale-[1.02] transition-all duration-200 border border-sand-200 dark:border-ink-700">
                    <h4 class="text-xl font-bold mb-3 text-ink-900 dark:text-copper-light">
                        Smart Itineraries
                    </h4>
                    <p class="text-ink-900 dark:text-sand-100 leading-relaxed">
                        Organize flights, hotels, and activities — all in one streamlined dashboard
                        designed for clarity and ease.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white dark:bg-sand-900 rounded-3xl p-8 shadow-soft
                            hover:shadow-glow hover:scale-[1.02] transition-all duration-200 border border-sand-200 dark:border-ink-700">
                    <h4 class="text-xl font-bold mb-3 text-ink-900 dark:text-copper-light">
                        Preference Profiles
                    </h4>
                    <p class="text-ink-900 dark:text-sand-100 leading-relaxed">
                        Save your travel styles and interests to get personalized itinerary
                        recommendations that truly fit you.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white dark:bg-sand-900 rounded-3xl p-8 shadow-soft
                            hover:shadow-glow hover:scale-[1.02] transition-all duration-200 border border-sand-200 dark:border-ink-700">
                    <h4 class="text-xl font-bold mb-3 text-ink-900 dark:text-copper-light">
                        Share & Export
                    </h4>
                    <p class="text-ink-900 dark:text-sand-100 leading-relaxed">
                        Seamlessly share itineraries with travel companions, or export beautiful,
                        print-ready PDFs for offline access.
                    </p>
                </div>

            </div>
        </div>
    </section>


    <!-- Team -->
    <section id="team"
        class="py-24 px-6 lg:px-20 bg-sand dark:bg-sand-900 text-center transition-colors duration-500">

        <div x-data x-intersect="$el.classList.add('opacity-100','translate-y-0')"
             class="opacity-0 translate-y-6 transition-all duration-700 ease-out">

            <h3 class="text-3xl font-extrabold mb-12 text-ink-900 dark:text-sand-100">
                Meet the Team
            </h3>

            <div class="flex flex-wrap justify-center gap-12">

                @foreach ([ 
                    ['Balemual Ymamu', 'Balemual Headshot.jpg', 'Software & Database Developer',
                     'Balemual Ymamu is a senior at ODU majoring in Computer Science. He is eager to gain hands-on experience in the tech field and looks forward to working as a Software Developer after graduation.'],

                    ['Crystal Rivas', 'Crystal Headshot.png', 'Software & Database Developer',
                     'Crystal Rivas is a second-degree-seeking student in Computer Science at ODU with a background in math education. Her expertise in problem-solving drives the transition and aims to develop innovative and impactful solutions.'],

                ] as $member)

                    <div class="w-full sm:w-[22rem] bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700
                                rounded-3xl shadow-soft hover:shadow-glow hover:scale-[1.02]
                                transition-all duration-300 ease-out p-8 text-center">

                        <img src="{{ asset('data/images/TeamHeadshots/' . $member[1]) }}"
                             alt="{{ $member[0] }}"
                             class="rounded-full mx-auto mb-4 w-32 h-32 object-cover shadow-md">

                        <h4 class="font-bold text-lg text-ink-900 dark:text-sand-100">
                            {{ $member[0] }}
                        </h4>

                        <p class="text-sm font-semibold text-ink-900 dark:text-copper-light mb-3">
                            {{ $member[2] }}
                        </p>

                        <p class="text-ink-900 dark:text-sand-100 leading-relaxed">
                            {{ $member[3] }}
                        </p>
                    </div>

                @endforeach

            </div>


            <!-- Expandable Team -->
            <div class="mt-10" x-data="{ open: false }">

                <button @click="open = !open"
                        class="px-6 py-2 rounded-full bg-gradient-copper text-white font-semibold
                               hover:shadow-glow hover:scale-[1.03] transition-all">
                    <span x-show="!open">Show More</span>
                    <span x-show="open">Show Less</span>
                </button>

                <div x-show="open"
                     x-transition.opacity.duration.300ms
                     class="grid gap-12 md:grid-cols-2 lg:grid-cols-3 mt-12">

                    @foreach ([
                        ['Fredrick Terling','Fredrick Headshot.jpg','Software Developer',
                         'Fredrick Terling is a senior at ODU majoring in Computer Science. He is currently hoping to get his foot in the door for most tech job opportunities but dreams of being a Game Developer and ultimately a Game Producer.'],

                        ['Jandra D. Arias Tavarez','Jandra Headshot.jpg','Software & Database Developer',
                         'Jandra D. Arias Tavarez is a second degree seeking student at ODU working on her bachelor in Computer Science. She aspires to work as a Software Developer after graduating.'],

                        ['William Poston','William P. Headshot.jpg','Software Developer & Webmaster',
                         'William Poston is a senior at ODU majoring in computer science with a minor in data science. After school, he dreams of being an AI prompt engineer.'],

                        ['William Mbandi','William M. Headshot.jpg','Software Developer',
                         'William Mbandi is a senior at ODU majoring in computer science with a minor in cyber security. Wants to work as a Software Developer after he graduates.'],

                        ['Stephen Usselman','Stephen Headshot.jpg','Software Developer & Designer',
                         'Stephen Usselman is a senior at ODU majoring in computer science. He is looking to pursue a career as a Software Developer after university.'],

                        ['Jacob Cook','Jacob Headshot.jpg','Mentor',
                         'Jacob Cook is an ODU Computer Science alumnus, IT Instructor at the Southern Virginia Higher Education Center, and founder/CEO of Southside Tech Services. He has built a career spanning software development, general IT, and hands-on education, while also guiding students and businesses in adopting technology effectively. Jacob is passionate about mentoring, and he enjoys helping students bridge the gap between classroom learning and the real-world challenges of a career in technology.'],

                    ] as $member)

                        <div class="rounded-3xl bg-white dark:bg-sand-800 shadow-soft hover:shadow-glow hover:scale-[1.02]
                                    transition-all p-8 border border-sand-200 dark:border-ink-700">

                            <img src="{{ asset('data/images/TeamHeadshots/' . $member[1]) }}"
                                 alt="{{ $member[0] }}"
                                 class="rounded-full mx-auto mb-4 w-32 h-32 object-cover">

                            <h4 class="font-bold text-lg text-ink-900 dark:text-sand-100">
                                {{ $member[0] }}
                            </h4>

                            <p class="text-sm font-semibold text-ink-900 dark:text-copper-light mb-3">
                                {{ $member[2] }}
                            </p>

                            <p class="text-ink-900 dark:text-sand-100 leading-relaxed">
                                {{ $member[3] }}
                            </p>
                        </div>

                    @endforeach

                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section id="contact"
        class="py-24 px-6 lg:px-20 bg-sand-100 dark:bg-sand-800 text-center transition-colors duration-500">

        <div x-data x-intersect="$el.classList.add('opacity-100','translate-y-0')"
             class="opacity-0 translate-y-6 transition-all duration-700 ease-out">

            <h3 class="text-3xl font-extrabold mb-6 text-ink-900 dark:text-sand-100">
                Contact Us
            </h3>

            <p class="max-w-2xl mx-auto text-lg mb-8 text-ink-900 dark:text-sand-100 leading-relaxed">
                Have questions or want to collaborate? We’d love to hear from you.
            </p>

            <a href="mailto:contact@itinerEase.com"
               class="inline-block px-6 py-3 rounded-full bg-gradient-copper text-white font-semibold
                      shadow-soft hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                Email Us
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-8 bg-sand dark:bg-sand-800 border-t border-sand-200 dark:border-ink-700 text-center text-sm transition-colors duration-500">
        <p class="text-ink-900 dark:text-sand-100">
            &copy; {{ date('Y') }} ItinerEase. All rights reserved.
        </p>
    </footer>

</body>
</html>
