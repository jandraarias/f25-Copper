<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between py-4 px-2 
            bg-gradient-to-r from-copper-100/60 to-transparent 
            dark:from-copper-900/20 rounded-2xl shadow-soft">

            <h2 class="text-3xl font-bold tracking-tight text-ink-900 dark:text-sand-100 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-7 h-7 text-copper"
                     fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6h4m-6 0H7m6 0v-2a4 4 0 00-4-4H9m4 6H5v-2a4 4 0 014-4h0" />
                </svg>
                Travelers
            </h2>
        </div>
    </x-slot>


    {{-- MAIN CONTENT --}}
    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">

            {{-- Empty State --}}
            @if($travelers->isEmpty())
                <div class="p-10 text-center text-ink-700 dark:text-sand-200 bg-white dark:bg-sand-800
                    border border-sand-200 dark:border-ink-700 rounded-2xl shadow-soft">
                    <p class="text-xl font-semibold">No travelers found.</p>
                    <p class="text-sm mt-2 opacity-70">Travelers you’re assisting will appear here.</p>
                </div>
            @endif


            {{-- Travelers Grid --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-6">

                @foreach ($travelers as $traveler)
                    <div class="group bg-white dark:bg-sand-800 rounded-3xl shadow-soft 
                                border border-sand-200 dark:border-ink-700 p-6 transition-all 
                                hover:shadow-glow hover:scale-[1.02] flex flex-col">

                        {{-- Photo --}}
                        <img src="{{ $traveler->photo_url ?: 'https://via.placeholder.com/300' }}"
                             class="w-full h-56 object-cover rounded-2xl shadow mb-5"
                             alt="{{ $traveler->user->name }}">

                        {{-- Name --}}
                        <h3 class="text-2xl font-bold text-ink-900 dark:text-sand-100 mb-2">
                            {{ $traveler->user->name }}
                        </h3>

                        {{-- Email --}}
                        <p class="text-sm text-ink-700 dark:text-sand-300 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-4 h-4 text-copper"
                                 fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 12H8m0 0l4 4m-4-4l4-4" />
                            </svg>
                            {{ $traveler->user->email }}
                        </p>

                        {{-- Phone --}}
                        @if($traveler->user->phone_number)
                            <p class="text-sm mt-2 text-ink-700 dark:text-sand-300 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-4 h-4 text-copper"
                                     fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 5a2 2 0 012-2h2l2 5-2 1 4 7 4-2 1 2H7a2 2 0 01-2-2V5z" />
                                </svg>
                                {{ $traveler->user->phone_number }}
                            </p>
                        @endif

                        {{-- DOB --}}
                        @if($traveler->user->date_of_birth)
                            <p class="text-sm mt-2 text-ink-700 dark:text-sand-300">
                                <strong>DOB:</strong> {{ $traveler->user->date_of_birth->format('M d, Y') }}
                            </p>
                        @endif

                        {{-- Last Active --}}
                        <p class="text-xs mt-3 text-ink-500 dark:text-sand-300">
                            Last active: {{ $traveler->updated_at->diffForHumans() }}
                        </p>


                        {{-- CTA Buttons --}}
                        <div class="mt-6 flex flex-col gap-3">

                            {{-- View Profile --}}
                            <a href="{{ route('expert.travelers.show', $traveler) }}"
                               class="w-full text-center px-5 py-2.5 rounded-full 
                                    border border-copper text-copper font-medium 
                                    hover:bg-copper hover:text-white hover:shadow-glow 
                                    hover:scale-[1.02] transition-all duration-200">
                                View Profile
                            </a>

                            {{-- Message --}}
                            <a href="{{ route('expert.messages.show', $traveler)}}"
                               class="w-full text-center px-5 py-2.5 rounded-full 
                                    bg-gradient-copper text-white font-semibold shadow-soft
                                    hover:shadow-glow hover:scale-[1.02] transition-all duration-200">
                                Message Traveler
                            </a>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>
    </div>

</x-app-layout>
