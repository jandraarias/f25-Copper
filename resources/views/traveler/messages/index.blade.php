<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between py-4 px-2 
            bg-gradient-to-r from-copper-100/60 to-transparent 
            dark:from-copper-900/20 rounded-2xl shadow-soft">

            <h2 class="text-3xl font-bold tracking-tight text-ink-900 dark:text-sand-100 
                       flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-7 h-7 text-copper"
                     fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 8h1a3 3 0 013 3v8a3 3 0 01-3 3H6a3 3 0 01-3-3v-8a3 3 0 
                           013-3h1m10-3a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Messages
            </h2>
        </div>
    </x-slot>


    {{-- MAIN CONTENT --}}
    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Empty State --}}
            @if($expertConversations->isEmpty())
                <div class="bg-white dark:bg-sand-800 p-12 rounded-3xl shadow-soft 
                            border border-sand-200 dark:border-ink-700 text-center">
                    <p class="text-xl font-semibold text-ink-900 dark:text-sand-100">
                        No Conversations Yet
                    </p>
                    <p class="text-sm text-ink-600 dark:text-sand-300 mt-2">
                        Message a local expert to start a conversation.
                    </p>
                </div>
            @endif


            {{-- Conversation List --}}
            <div class="space-y-4">

                @foreach ($expertConversations as $expert)

                    @php
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
                              border border-sand-200 dark:border-ink-700 shadow-soft hover:shadow-glow 
                              hover:scale-[1.01] transition-all duration-200 ease-out group">

                        {{-- Avatar --}}
                        <img src="{{ $expert->photo_url ?? 'https://via.placeholder.com/150' }}"
                             class="w-16 h-16 rounded-2xl object-cover shadow-md"
                             alt="{{ $expert->user->name }}">

                        {{-- Details --}}
                        <div class="flex-1">

                            {{-- Name + City --}}
                            <div class="flex items-center gap-2">
                                <p class="text-lg font-bold text-ink-900 dark:text-sand-100">
                                    {{ $expert->user->name }}
                                </p>

                                @if($unread > 0)
                                    <span class="ml-2 bg-copper text-white text-xs px-2 py-0.5 
                                                 rounded-full shadow-md">
                                        {{ $unread }} new
                                    </span>
                                @endif
                            </div>

                            <p class="text-sm text-ink-600 dark:text-sand-300">
                                {{ $expert->city }}
                            </p>

                            {{-- Last message preview --}}
                            <p class="text-sm mt-2 text-ink-700 dark:text-sand-200 line-clamp-1">
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

        </div>
    </div>

</x-app-layout>
