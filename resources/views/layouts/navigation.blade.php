<nav x-data="{ open: false, darkMode: localStorage.getItem('theme') === 'dark' }"
     x-init="if (darkMode) document.documentElement.classList.add('dark')"
     class="bg-white dark:bg-sand-800 border-b border-sand-200 dark:border-ink-700
            shadow-sm transition-colors duration-500 ease-in-out">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center">
                        {{-- Light Mode Logo --}}
                        <img 
                            src="{{ asset('data/images/logos/itinerease-logo-dark@2x.svg') }}" 
                            alt="ItinerEase Logo" 
                            class="h-8 block dark:hidden transition-transform duration-300 hover:scale-105"
                        >
                        {{-- Dark Mode Logo --}}
                        <img 
                            src="{{ asset('data/images/logos/itinerease-logo-light.svg') }}" 
                            alt="ItinerEase Logo" 
                            class="h-8 hidden dark:block transition-transform duration-300 hover:scale-105"
                        >
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        @if(auth()->user()->isTraveler())
                            @if (Route::has('traveler.dashboard'))
                                <x-nav-link :href="route('traveler.dashboard')" :active="request()->routeIs('traveler.dashboard')">
                                    {{ __('Dashboard') }}
                                </x-nav-link>
                            @endif

                            @if (Route::has('traveler.itineraries.index'))
                                <x-nav-link :href="route('traveler.itineraries.index')" :active="request()->routeIs('traveler.itineraries.*')">
                                    {{ __('Itineraries') }}
                                </x-nav-link>
                            @endif

                            @if (Route::has('traveler.preference-profiles.index'))
                                <x-nav-link :href="route('traveler.preference-profiles.index')" :active="request()->routeIs('traveler.preference-profiles.*')">
                                    {{ __('Preferences') }}
                                </x-nav-link>
                            @endif
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Right Side Controls -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                {{-- Theme Toggle --}}
                <button @click="
                    darkMode = !darkMode;
                    if (darkMode) {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    }"
                    class="flex items-center gap-2 px-3 py-1.5 rounded-full border border-sand-300 dark:border-ink-600 
                           text-ink-700 dark:text-sand-200 hover:bg-sand-100 dark:hover:bg-ink-700 
                           transition-all duration-300 ease-out"
                    title="Toggle dark mode">

                    {{-- light --}}
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-copper"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 3v1m0 16v1m9-9h1M4 12H3m15.364-7.364l.707.707M6.343 17.657l-.707.707m0-12.02l.707.707M18.364 17.657l.707.707" />
                    </svg>

                    {{-- dark --}}
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-copper"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
                    </svg>
                </button>

                {{-- User Dropdown --}}
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent 
                                           text-sm leading-4 font-medium rounded-md text-ink-700 dark:text-sand-200 
                                           bg-transparent hover:text-copper focus:outline-none transition duration-150 ease-in-out">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            <!-- Mobile Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md 
                               text-ink-700 dark:text-sand-200 hover:text-copper hover:bg-sand-100 dark:hover:bg-ink-700 
                               transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>
