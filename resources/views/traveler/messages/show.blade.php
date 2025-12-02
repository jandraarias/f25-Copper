<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between py-4 px-2
            bg-gradient-to-r from-copper-100/60 to-transparent
            dark:from-copper-900/20 rounded-2xl shadow-soft">

            <div class="flex items-center gap-4">
                {{-- Expert Avatar --}}
                <img src="{{ $expert->profile_photo_url ?? asset('storage/images/defaults/expert.png') }}"
                    class="w-12 h-12 rounded-2xl object-cover shadow 
                            border border-sand-300 dark:border-ink-700"
                    alt="{{ $expert->name }}">

                {{-- Name --}}
                <h2 class="text-3xl font-bold text-ink-900 dark:text-sand-100">
                    Messaging — {{ $expert->user->name }}
                </h2>
            </div>

            <a href="{{ route('traveler.messages.index') }}"
               class="group flex items-center gap-2 px-5 py-2.5 rounded-full 
                    border border-copper text-copper 
                    hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03]
                    transition-all duration-200 ease-out">
                ← Back
            </a>
        </div>
    </x-slot>

    {{-- MAIN CONTENT --}}
    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="max-w-3xl mx-auto space-y-8">

            {{-- Chat Window --}}
            <div id="chat-container"
                 class="bg-white dark:bg-sand-800 rounded-3xl shadow-soft border border-sand-300 dark:border-ink-700 
                        p-6 h-[70vh] overflow-y-auto space-y-4">

                {{-- Empty Chat --}}
                @if($messages->isEmpty())
                    <p class="text-center text-ink-600 dark:text-sand-300 opacity-70 mt-20">
                        No messages yet — start the conversation!
                    </p>
                @endif

                {{-- Messages --}}
                @foreach($messages as $message)
                    <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">

                        {{-- Avatar (left only) --}}
                        @if ($message->sender_id !== auth()->id())
                            <img src="{{ $expert->profile_photo_url ?? asset('storage/images/defaults/expert.png') }}"
                                class="w-10 h-10 rounded-2xl object-cover shadow mr-2
                                        border border-sand-300 dark:border-ink-700"
                                alt="{{ $expert->name }}">
                        @endif

                        {{-- Message bubble --}}
                        <div class="max-w-xs p-4 rounded-2xl 
                            {{ $message->sender_id === auth()->id() 
                                ? 'bg-gradient-copper text-white' 
                                : 'bg-sand-200 dark:bg-sand-700 text-ink-900 dark:text-sand-100' }}">

                            <p>{{ $message->content }}</p>

                            <p class="text-xs mt-1 opacity-70">
                                {{ $message->created_at->format('M d, g:i A') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Message Form --}}
            <form action="{{ route('traveler.messages.store', $expert) }}" method="POST"
                  class="flex gap-3">
                @csrf

                <input type="text" name="content"
                       class="flex-1 rounded-xl border-sand-300 dark:border-ink-700 dark:bg-sand-800
                              text-ink-900 dark:text-sand-100 px-4 py-3 shadow-soft"
                       placeholder="Write a message..." required>

                <button class="px-6 py-3 rounded-xl bg-gradient-copper text-white 
                        font-semibold shadow-soft hover:shadow-glow hover:scale-[1.03] transition-all">
                    Send
                </button>
            </form>

        </div>
    </div>

    {{-- Auto-scroll to bottom --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chat = document.getElementById('chat-container');
            if (chat) chat.scrollTop = chat.scrollHeight;
        });
    </script>

</x-app-layout>
