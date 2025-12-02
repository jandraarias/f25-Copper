<!-- resources/views/layouts/navigation.blade.php -->

<nav x-data="{ open: false, darkMode: localStorage.getItem('theme') === 'dark' }"
     x-init="if (darkMode) document.documentElement.classList.add('dark')"
     class="bg-white dark:bg-sand-800 border-b border-sand-200 dark:border-ink-700
            shadow-sm transition-colors duration-500 ease-in-out">

    <!-- Max Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Desktop Bar -->
        <div class="flex justify-between h-16">

            <!-- Left Section: Logo + Desktop Nav -->
            <div class="flex items-center">

                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center shrink-0">
                    {{-- Light Mode Logo --}}
                    <img src="{{ asset('data/images/logos/itinerease-logo-dark@2x.svg') }}"
                         class="h-8 block dark:hidden transition-transform duration-300 hover:scale-105"
                         alt="ItinerEase Logo">

                    {{-- Dark Mode Logo --}}
                    <img src="{{ asset('data/images/logos/itinerease-logo-light.svg') }}"
                         class="h-8 hidden dark:block transition-transform duration-300 hover:scale-105"
                         alt="ItinerEase Logo">
                </a>

                <!-- Desktop Nav Links -->
                <div class="hidden sm:flex sm:space-x-8 sm:ml-10">

                    @auth
                        <!-- Travelers -->
                        @if(auth()->user()->isTraveler())

                            <!-- Dashboard -->
                            <x-nav-link :href="route('traveler.dashboard')"
                                        :active="request()->routeIs('traveler.dashboard')">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-copper" fill="none" stroke="currentColor" stroke-width="1.5"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h5m5 0h5a1 1 0 001-1V10" />
                                    </svg>
                                    {{ __('Dashboard') }}
                                </div>
                            </x-nav-link>

                            <!-- Itineraries -->
                            <x-nav-link :href="route('traveler.itineraries.index')"
                                        :active="request()->routeIs('traveler.itineraries.*')">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-copper" fill="none" stroke="currentColor" stroke-width="1.5"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M3 7h18M3 12h18M3 17h18" />
                                    </svg>
                                    {{ __('Itineraries') }}
                                </div>
                            </x-nav-link>

                            <!-- Preferences -->
                            <x-nav-link :href="route('traveler.preference-profiles.index')"
                                        :active="request()->routeIs('traveler.preference-profiles.*')">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-forest" fill="none" stroke="currentColor" stroke-width="1.5"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 3v18m9-9H3" />
                                    </svg>
                                    {{ __('Preferences') }}
                                </div>
                            </x-nav-link>

                            <!-- Experts (NEW) -->
                            <x-nav-link :href="route('traveler.experts')"
                                        :active="request()->routeIs('traveler.experts')">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-copper" fill="none" stroke="currentColor" stroke-width="1.5"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 2-8 4v2h16v-2c0-2-3.582-4-8-4z" />
                                    </svg>
                                    {{ __('Experts') }}
                                </div>
                            </x-nav-link>

                            <!-- Rewards -->
                            <x-nav-link :href="route('traveler.rewards')"
                                        :active="request()->routeIs('traveler.rewards')">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-copper" fill="none" stroke="currentColor" stroke-width="1.5"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M11.3 2.3a1 1 0 011.4 0l2 3.7 4.1.6a1 1 0 01.6 1.7l-3 2.9.7 4a1 1 0 01-1.5 1l-3.6-1.9-3.6 1.9a1 1 0 01-1.5-1l.7-4-3-2.9a1 1 0 01.6-1.7l4.1-.6 2-3.7z" />
                                    </svg>
                                    {{ __('Rewards') }}
                                </div>
                            </x-nav-link>

                        @endif

                        <!-- Experts -->
                        @if(auth()->user()->isExpert())
                        <!-- Expert Dashboard -->
                        <x-nav-link :href="route('expert.dashboard')"
                                    :active="request()->routeIs('expert.dashboard')">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-copper" fill="none" stroke="currentColor" stroke-width="1.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h5m5 0h5a1 1 0 001-1V10" />
                                </svg>
                                {{ __('Dashboard') }}
                            </div>
                        </x-nav-link>

                        <!-- Expert Itineraries -->
                        <x-nav-link :href="route('expert.itineraries.index')"
                                    :active="request()->routeIs('expert.itineraries.*')">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-copper" fill="none" stroke="currentColor" stroke-width="1.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 7h18M3 12h18M3 17h18" />
                                </svg>
                                {{ __('Itineraries') }}
                            </div>
                        </x-nav-link>

                        <!-- Travelers for Experts -->
                        <x-nav-link :href="route('expert.travelers.index')"
                                    :active="request()->routeIs('expert.travelers.*')">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-copper" fill="none" stroke="currentColor" stroke-width="1.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 2-8 4v2h16v-2c0-2-3.582-4-8-4z" />
                                </svg>
                                {{ __('Travelers') }}
                            </div>
                        </x-nav-link>

                        <!-- Expert Profile -->
                        <x-nav-link :href="route('expert.profile.edit')"
                                    :active="request()->routeIs('expert.profile.*')">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-forest" fill="none" stroke="currentColor" stroke-width="1.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3v18m9-9H3" />
                                </svg>
                                {{ __('Profile') }}
                            </div>
                        </x-nav-link>
                    @endif

                    @endauth

                </div>
            </div>

            <!-- Right Section: Theme Toggle + User Menu -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">

                {{-- Theme Toggle Button --}}
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
                           text-ink-700 dark:text-sand-100 hover:bg-sand-100 dark:hover:bg-ink-700 
                           transition-all duration-300 ease-out">

                    {{-- Light Icon --}}
                    <svg x-show="!darkMode" class="w-5 h-5 text-copper" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 3v1m0 16v1m9-9h1M4 12H3m15.364-7.364l.707.707M6.343 17.657l-.707.707m0-12.02l.707.707M18.364 17.657l.707.707" />
                    </svg>

                    {{-- Dark Icon --}}
                    <svg x-show="darkMode" class="w-5 h-5 text-copper" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
                    </svg>

                </button>

                {{-- User Dropdown --}}
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium
                                           rounded-md text-ink-700 dark:text-sand-100 hover:text-copper
                                           transition duration-150 ease-in-out">
                                <div>{{ Auth::user()->name }}</div>

                                <svg class="ms-1 fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" 
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 
                                          111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd" />
                                </svg>
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
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-md 
                               text-ink-700 dark:text-sand-100 hover:text-copper hover:bg-sand-100 dark:hover:bg-ink-700 
                               transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                              class="inline-flex" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                              class="hidden" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div :class="{ 'block': open, 'hidden': !open }"
         class="sm:hidden bg-white dark:bg-sand-800 border-t border-sand-200 dark:border-ink-700 px-4 py-4 space-y-2">

        @auth
            <!-- Travelers -->
            @if(auth()->user()->isTraveler())

                <x-responsive-nav-link :href="route('traveler.dashboard')"
                                       :active="request()->routeIs('traveler.dashboard')">
                    Dashboard
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('traveler.itineraries.index')"
                                       :active="request()->routeIs('traveler.itineraries.*')">
                    Itineraries
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('traveler.experts')"
                                       :active="request()->routeIs('traveler.experts')">
                    Experts
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('traveler.preference-profiles.index')"
                                       :active="request()->routeIs('traveler.preference-profiles.*')">
                    Preferences
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('traveler.rewards')"
                                       :active="request()->routeIs('traveler.rewards')">
                    Rewards
                </x-responsive-nav-link>
            @endif

            <!-- Experts -->
            @if(auth()->user()->isExpert())

                <x-responsive-nav-link :href="route('expert.dashboard')"
                                    :active="request()->routeIs('expert.dashboard')">
                    Dashboard
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('expert.itineraries.index')"
                                    :active="request()->routeIs('expert.itineraries.*')">
                    Itineraries
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('expert.travelers.index')"
                                    :active="request()->routeIs('expert.travelers.*')">
                    Travelers
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('expert.profile.edit')"
                                    :active="request()->routeIs('expert.profile.*')">
                    Profile
                </x-responsive-nav-link>
            @endif

        @endauth

        <!-- Divider -->
        <div class="border-t border-sand-300 dark:border-ink-600 my-3"></div>

        <!-- Mobile Theme Toggle -->
        <button @click="
            darkMode = !darkMode;
            if (darkMode) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }"
            class="w-full flex items-center gap-3 px-4 py-2 text-sm rounded-md 
                   text-ink-700 dark:text-sand-100 hover:bg-sand-100 dark:hover:bg-ink-700 
                   transition duration-150 ease-in-out">

            <span>{{ __('Toggle Theme') }}</span>

            <svg x-show="!darkMode" class="w-5 h-5 text-copper" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 3v1m0 16v1m9-9h1M4 12H3m15.364-7.364l.707.707M6.343 17.657l-.707.707m0-12.02l.707.707M18.364 17.657l.707.707" />
            </svg>

            <svg x-show="darkMode" class="w-5 h-5 text-copper" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
            </svg>
        </button>

        <!-- Mobile User Controls -->
        @auth
            <x-responsive-nav-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        @endauth
    </div>
</nav>
