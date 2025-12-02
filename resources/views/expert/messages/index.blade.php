<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between py-4 px-2
            bg-gradient-to-r from-copper-100/60 to-transparent
            dark:from-copper-900/20 rounded-2xl shadow-soft">

            <h2 class="text-3xl font-bold text-ink-900 dark:text-sand-100 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-7 h-7 text-copper"
                     fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M7 8h10M7 12h6m-6 4h10" />
                </svg>
                Messages
            </h2>
        </div>
    </x-slot>


    {{-- MAIN CONTENT --}}
    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="max-w-3xl mx-auto space-y-8">

            {{-- Empty state --}}
            @if($threads->isEmpty())
                <div class="p-10 text-center bg-white dark:bg-sand-800 rounded-3xl
                            border border-sand-300 dark:border-ink-700 shadow-soft">
                    <p class="text-xl text-ink-700 dark:text-sand-200">No messages yet.</p>
                    <p class="text-sm opacity-70 mt-2">Conversations with travelers will appear here.</p>
                </div>
            @else

                {{-- Conversation List --}}
                <div class="space-y-4">
                    @foreach($threads as $traveler)
                        <a href="{{ route('expert.messages.show', $traveler) }}"
                           class="flex items-center gap-4 p-5 bg-white dark:bg-sand-800
                                  rounded-2xl border border-sand-300 dark:border-ink-700 shadow-soft
                                  hover:shadow-glow hover:scale-[1.02] transition-all duration-200">

                            {{-- Avatar --}}
                            <img src="{{ $traveler->user->profile_photo_url ?? asset('storage/images/defaults/traveler.png') }}"
                                class="w-14 h-14 rounded-2xl object-cover shadow 
                                        border border-sand-300 dark:border-ink-700"
                                alt="Traveler avatar">

                            <div class="flex-1">
                                <p class="text-lg font-semibold text-ink-900 dark:text-sand-100">
                                    {{ $traveler->user->name }}
                                </p>
                                <p class="text-sm text-ink-600 dark:text-sand-300 line-clamp-1">
                                    {{ $traveler->last_message?->content ?? 'No messages yet.' }}
                                </p>
                            </div>

                            <div class="text-xs text-ink-500 dark:text-sand-400">
                                {{ optional($traveler->last_message?->created_at)->diffForHumans() }}
                            </div>
                        </a>
                    @endforeach
                </div>

            @endif

        </div>
    </div>

</x-app-layout>
