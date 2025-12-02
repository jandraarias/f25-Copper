<x-app-layout>

    {{-- ======= UNIFORM HEADER ======= --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-200 flex items-center gap-2">
                Messages
            </h2>

            <a href="{{ route('traveler.messages.index') }}"
            class="px-5 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft
                    hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                Inbox
            </a>
        </div>
    </x-slot>


    {{-- ======= MAIN CONTENT ======= --}}
    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen transition-colors duration-300">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- ===== EMPTY STATE ===== --}}
            @if($expertConversations->isEmpty())
                <div class="bg-white dark:bg-sand-800 p-12 rounded-3xl shadow-soft 
                            border border-sand-200 dark:border-ink-700 text-center
                            hover:shadow-glow hover:scale-[1.01] transition-all duration-200 ease-out">

                    <div class="flex justify-center mb-4">
                        <div class="w-14 h-14 flex items-center justify-center rounded-full bg-copper/10">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-7 h-7 text-copper"
                                 fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17 8h1a3 3 0 013 3v8a3 3 0 01-3 3H6a3 3 0 
                                         01-3-3v-8a3 3 0 013-3h1m10-3a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>

                    <p class="text-xl font-semibold text-ink-900 dark:text-sand-100">
                        No Conversations Yet
                    </p>

                    <p class="text-sm text-ink-600 dark:text-sand-300 mt-2">
                        Connect with local experts to start your travel planning.
                    </p>

                    <a href="{{ route('traveler.experts.index') }}"
                       class="inline-block mt-6 px-6 py-2.5 rounded-full bg-gradient-copper text-white
                              shadow-soft hover:shadow-glow hover:scale-[1.03]
                              transition-all duration-200 ease-out font-medium">
                        Browse Experts
                    </a>
                </div>
            @endif


            {{-- ===== MESSAGE LIST ===== --}}
            @if(!$expertConversations->isEmpty())
                <div class="space-y-4">

                    @foreach ($expertConversations as $expert)
                        @php
                            // Find last message sent in either direction
                            $lastMessage = $expert->user->sentMessages()
                                ->where('receiver_id', auth()->id())
                                ->latest()
                                ->first()
                                ??
                                $expert->user->receivedMessages()
                                    ->where('sender_id', auth()->id())
                                    ->latest()
                                    ->first();

                            $unread = $expert->user->sentMessages()
                                ->where('receiver_id', auth()->id())
                                ->where('is_read', false)
                                ->count();
                        @endphp

                        <a href="{{ route('traveler.messages.show', $expert) }}"
                           class="flex items-center gap-5 p-6 rounded-3xl bg-white dark:bg-sand-800 
                                  border border-sand-200 dark:border-ink-700 shadow-soft 
                                  hover:shadow-glow hover:scale-[1.01] transition-all duration-200 ease-out group">

                            {{-- Avatar --}}
                            <div class="relative">
                                <img src="{{ $expert->profile_photo_url ?? asset('storage/images/defaults/expert.png') }}"
                                    class="w-16 h-16 rounded-2xl object-cover shadow-md"
                                    alt="{{ $expert->user->name }}">
                                    
                                @if ($unread > 0)
                                    <span class="absolute -top-1 -right-1 bg-copper text-white text-xs px-2 py-0.5 rounded-full shadow">
                                        {{ $unread }}
                                    </span>
                                @endif
                            </div>

                            {{-- Details --}}
                            <div class="flex-1 space-y-1">

                                <div class="flex items-center gap-2">
                                    <p class="text-lg font-bold text-ink-900 dark:text-sand-100">
                                        {{ $expert->user->name }}
                                    </p>
                                </div>

                                <p class="text-sm text-ink-600 dark:text-sand-300">
                                    {{ $expert->city }}
                                </p>

                                {{-- Message Preview --}}
                                <p class="text-sm text-ink-700 dark:text-sand-200 mt-2 line-clamp-1">
                                    @if($lastMessage)
                                        {{ $lastMessage->sender_id === auth()->id() ? 'You: ' : '' }}
                                        {{ $lastMessage->content }}
                                    @else
                                        <em>No messages yet</em>
                                    @endif
                                </p>

                            </div>

                            {{-- Timestamp --}}
                            @if($lastMessage)
                                <p class="text-xs text-ink-500 dark:text-sand-400 whitespace-nowrap">
                                    {{ $lastMessage->created_at->diffForHumans() }}
                                </p>
                            @endif

                        </a>
                    @endforeach

                </div>
            @endif

        </div>
    </div>

</x-app-layout>
