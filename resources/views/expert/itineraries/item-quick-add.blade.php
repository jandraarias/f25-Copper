{{-- resources/views/expert/itineraries/item-quick-add.blade.php --}}

<tr x-data="{ addOpen: false }">
    <td colspan="5" class="bg-sand-50 dark:bg-sand-900/40 p-4 border-b border-sand-200 dark:border-ink-700">

        {{-- Toggle Button --}}
        <button type="button"
                @click="addOpen = !addOpen"
                class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-full border border-copper text-copper
                       hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.02]
                       transition-all duration-200 ease-out font-medium">

            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>

            <span x-show="!addOpen">Quick Add Item</span>
            <span x-show="addOpen">Cancel</span>
        </button>

        {{-- Form --}}
        <div x-show="addOpen" x-transition.opacity.duration.200ms class="mt-6">
            <form method="POST"
                  action="{{ route('expert.items.store', $itinerary) }}"
                  class="grid grid-cols-1 md:grid-cols-5 gap-4">

                @csrf

                {{-- Type --}}
                <div>
                    <input name="type" type="text" placeholder="Type (e.g., Activity)"
                           class="w-full border rounded-xl shadow-sm px-3 py-2 text-sm"
                           required>
                </div>

                {{-- Title --}}
                <div class="md:col-span-2">
                    <input name="title" type="text" placeholder="Title (e.g., Museum Visit)"
                           class="w-full border rounded-xl shadow-sm px-3 py-2 text-sm"
                           required>
                </div>

                {{-- Start --}}
                <div>
                    <input name="start_time" type="datetime-local"
                           class="w-full border rounded-xl shadow-sm px-3 py-2 text-sm">
                </div>

                {{-- End --}}
                <div>
                    <input name="end_time" type="datetime-local"
                           class="w-full border rounded-xl shadow-sm px-3 py-2 text-sm">
                </div>

                {{-- Location --}}
                <div class="md:col-span-2">
                    <input name="location" type="text" placeholder="Location (optional)"
                           class="w-full border rounded-xl shadow-sm px-3 py-2 text-sm">
                </div>

                {{-- Details --}}
                <div class="md:col-span-3">
                    <input name="details" type="text" placeholder="Details (optional)"
                           class="w-full border rounded-xl shadow-sm px-3 py-2 text-sm">
                </div>

                {{-- Submit --}}
                <div class="md:col-span-5 flex justify-end mt-2">
                    <button type="submit"
                            class="px-5 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft
                                   hover:shadow-glow hover:scale-[1.03]">
                        Add Item
                    </button>
                </div>
            </form>
        </div>
    </td>
</tr>
