<!-- resources/views/layouts/navigation.blade.php -->

<nav x-data="{ open: false, darkMode: localStorage.getItem('theme') === 'dark' }"
     x-init="if (darkMode) document.documentElement.classList.add('dark')"
     class="bg-white dark:bg-sand-800 border-b border-sand-200 dark:border-ink-700
            shadow-sm transition-colors duration-500 ease-in-out">

    <!-- Max Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Desktop Bar -->
        <div class="flex justify-between h-16">

            <!-- Left Section -->
            <div class="flex items-center">

                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center shrink-0">
                    <img src="{{ asset('data/images/logos/itinerease-logo-dark@2x.svg') }}"
                         class="h-8 block dark:hidden transition-transform duration-300 hover:scale-105">
                    <img src="{{ asset('data/images/logos/itinerease-logo-light.svg') }}"
                         class="h-8 hidden dark:block transition-transform duration-300 hover:scale-105">
                </a>

                <!-- Desktop Nav -->
                <div class="hidden sm:flex sm:space-x-8 sm:ml-10">

                    @auth

                    <!-- ========================= -->
                    <!-- TRAVELER NAVIGATION -->
                    <!-- ========================= -->
                    @if(auth()->user()->isTraveler())

                        <!-- Dashboard -->
                        <x-nav-link :href="route('traveler.dashboard')"
                            :active="request()->routeIs('traveler.dashboard')">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-copper" fill="none" stroke="currentColor" 
                                     stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 12l9-9 9 9M4 10v10h6m4 0h6V10" />
                                </svg>
                                Dashboard
                            </div>
                        </x-nav-link>

                        <!-- Itineraries -->
                        <x-nav-link :href="route('traveler.itineraries.index')"
                            :active="request()->routeIs('traveler.itineraries.*')">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-copper" fill="none" stroke="currentColor"
                                     stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 7h18M3 12h18M3 17h18" />
                                </svg>
                                Itineraries
                            </div>
                        </x-nav-link>

                        <!-- Preferences -->
                        <x-nav-link :href="route('traveler.preference-profiles.index')"
                            :active="request()->routeIs('traveler.preference-profiles.*')">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-forest" fill="none" stroke="currentColor"
                                     stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 3v18m9-9H3" />
                                </svg>
                                Preferences
                            </div>
                        </x-nav-link>

                        <!-- Experts -->
                        <x-nav-link :href="route('traveler.experts.index')"
                            :active="request()->routeIs('traveler.experts')">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-copper" fill="none" stroke="currentColor"
                                     stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.4 0-8 2-8 4v2h16v-2c0-2-3.6-4-8-4z" />
                                </svg>
                                Experts
                            </div>
                        </x-nav-link>

                        <!-- ************************ -->
                        <!-- TRAVELER MESSAGES (NEW!) -->
                        <!-- ************************ -->
                        <x-nav-link :href="route('traveler.messages.index')"
                            :active="request()->routeIs('traveler.messages.*')">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-copper" fill="none" stroke="currentColor"
                                     stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M7 8h10M7 12h6m-9 8l3.5-3H20a2 2 0 002-2V6a2 2 0 00-2-2H4a2 2 0 00-2 2v14l3-3z" />
                                </svg>
                                Messages
                            </div>
                        </x-nav-link>

                        <!-- Rewards -->
                        <x-nav-link :href="route('traveler.rewards')"
                            :active="request()->routeIs('traveler.rewards')">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-copper" fill="none" stroke="currentColor"
                                     stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M11.3 2.3a1 1 0 011.4 0l2 3.7 4.1.6-3 2.9.7 4-3.6-1.9-3.6 1.9.7-4-3-2.9 4.1-.6 2-3.7z" />
                                </svg>
                                Rewards
                            </div>
                        </x-nav-link>

                    @endif



                    <!-- ===================== -->
                    <!-- EXPERT NAVIGATION -->
                    <!-- ===================== -->
                    @if(auth()->user()->isExpert())

                        <!-- Dashboard -->
                        <x-nav-link :href="route('expert.dashboard')"
                            :active="request()->routeIs('expert.dashboard')">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-copper" fill="none" stroke="currentColor"
                                     stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 12l9-9 9 9M4 10v10h6m4 0h6V10" />
                                </svg>
                                Dashboard
                            </div>
                        </x-nav-link>

                        <!-- Itineraries -->
                        <x-nav-link :href="route('expert.itineraries.index')"
                            :active="request()->routeIs('expert.itineraries.*')">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-copper" fill="none" stroke="currentColor"
                                     stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 7h18M3 12h18M3 17h18" />
                                </svg>
                                Itineraries
                            </div>
                        </x-nav-link>

                        <!-- Travelers -->
                        <x-nav-link :href="route('expert.travelers.index')"
                            :active="request()->routeIs('expert.travelers.*')">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-copper" fill="none" stroke="currentColor"
                                     stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.4 0-8 2-8 4v2h16v-2c0-2-3.6-4-8-4z" />
                                </svg>
                                Travelers
                            </div>
                        </x-nav-link>

                        <!-- ************************ -->
                        <!-- EXPERT MESSAGES (NEW!) -->
                        <!-- ************************ -->
                        <x-nav-link :href="route('expert.messages.index')"
                            :active="request()->routeIs('expert.messages.*')">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-copper" fill="none" stroke="currentColor"
                                     stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M7 8h10M7 12h6m-9 8l3.5-3H20a2 2 0 002-2V6a2 2 0 00-2-2H4a2 2 0 00-2 2v14l3-3z" />
                                </svg>
                                Messages
                            </div>
                        </x-nav-link>

                        <!-- Profile -->
                        <x-nav-link :href="route('expert.profile.show')"
                            :active="request()->routeIs('expert.profile.*')">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-forest" fill="none" stroke="currentColor"
                                     stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 3v18m9-9H3" />
                                </svg>
                                Profile
                            </div>
                        </x-nav-link>

                    @endif

                    @endauth

                </div>
            </div>




            <!-- USER + THEME SECTION -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">

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
                    class="flex items-center gap-2 px-3 py-1.5 rounded-full border border-sand-300 dark:border-ink-600 
                           text-ink-700 dark:text-sand-100 hover:bg-sand-100 dark:hover:bg-ink-700 
                           transition-all duration-300">

                    <svg x-show="!darkMode" class="w-5 h-5 text-copper" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 3v1m0 16v1m9-9h1M4 12H3m15-7l1 1m-1 13l1 1M6 5L5 6m1 12l-1 1" />
                    </svg>

                    <svg x-show="darkMode" class="w-5 h-5 text-copper" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 12.8A9 9 0 1111.2 3a7 7 0 009.8 9.8z" />
                    </svg>

                </button>

                <!-- User Dropdown -->
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium
                                           rounded-md text-ink-700 dark:text-sand-100 hover:text-copper
                                           transition duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <svg class="ms-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.3 7.3a1 1 0 011.4 0L10 10.6l3.3-3.3a1 1 0 111.4 1.4l-4 4a1 
                                          1 0 01-1.4 0l-4-4a1 1 0 010-1.4z" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">

                            <x-dropdown-link :href="route('profile.edit')">
                                Profile
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>

                        </x-slot>
                    </x-dropdown>
                @endauth

            </div>




            <!-- Mobile Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="p-2 rounded-md text-ink-700 dark:text-sand-100
                           hover:text-copper hover:bg-sand-100 dark:hover:bg-ink-700
                           transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                              class="inline-flex" stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                              class="hidden" stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>




    <!-- ==================================== -->
    <!-- MOBILE NAVIGATION DRAWER -->
    <!-- ==================================== -->
    <div :class="{ 'block': open, 'hidden': !open }"
         class="sm:hidden bg-white dark:bg-sand-800 border-t border-sand-200 dark:border-ink-700 px-4 py-4 space-y-2">

        @auth

        <!-- ========== Traveler Mobile Links ========== -->
        @if(auth()->user()->isTraveler())

            <x-responsive-nav-link :href="route('traveler.dashboard')" 
                                   :active="request()->routeIs('traveler.dashboard')">
                Dashboard
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('traveler.itineraries.index')" 
                                   :active="request()->routeIs('traveler.itineraries.*')">
                Itineraries
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('traveler.experts.index')" 
                                   :active="request()->routeIs('traveler.experts.*')">
                Experts
            </x-responsive-nav-link>

            <!-- NEW -->
            <x-responsive-nav-link :href="route('traveler.messages.index')" 
                                   :active="request()->routeIs('traveler.messages.*')">
                Messages
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




        <!-- ========== Expert Mobile Links ========== -->
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

            <!-- NEW -->
            <x-responsive-nav-link :href="route('expert.messages.index')" 
                                   :active="request()->routeIs('expert.messages.*')">
                Messages
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('expert.profile.show')" 
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
            class="w-full flex items-center gap-3 px-4 py-2 rounded-md text-sm
                   text-ink-700 dark:text-sand-100 hover:bg-sand-100 dark:hover:bg-ink-700
                   transition">
            Toggle Theme
        </button>


        <!-- Mobile User Controls -->
        @auth
            <x-responsive-nav-link :href="route('profile.edit')">
                Profile
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                    Log Out
                </x-responsive-nav-link>
            </form>
        @endauth
    </div>

</nav>
