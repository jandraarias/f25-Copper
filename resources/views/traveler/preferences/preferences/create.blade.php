{{-- resources/views/traveler/preferences/preferences/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Preference') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                {{-- Flash messages --}}
                <x-flash-messages />

                <form method="POST"
                      action="{{ route('traveler.preferences-profiles.preferences.store', $preferenceProfile) }}"
                      class="space-y-8">
                    @csrf

                    {{-- === Activity Preferences === --}}
                    <div
                        x-data="{
                            mainOptions: @js($mainOptions->map(fn($m) => ['id' => $m->id, 'name' => $m->name])->values()),
                            subMap: @js($subMap),
                            mainId: {{ old('main_interest_id') ? (int) old('main_interest_id') : 'null' }},
                            selectedSubs: @json(old('sub_interest_ids', [])),
                            addSub(id) {
                                if (!this.selectedSubs.includes(id)) this.selectedSubs.push(id)
                            },
                            removeSub(id) {
                                this.selectedSubs = this.selectedSubs.filter(s => s !== id)
                            }
                        }"
                        class="border-b border-gray-200 dark:border-gray-700 pb-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            Activity Preferences
                        </h3>

                        {{-- Main Interest --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Main Interest
                            </label>
                            <select x-model="mainId"
                                    name="main_interest_id"
                                    class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm"
                                    required>
                                <option value="">-- Select a main interest --</option>
                                <template x-for="m in mainOptions" :key="m.id">
                                    <option :value="m.id" x-text="m.name"></option>
                                </template>
                            </select>
                            @error('main_interest_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Sub-Interests Multi-Select Pills --}}
                        <template x-if="mainId">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Sub-Interests
                                </label>

                                {{-- Selected Pills --}}
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <template x-for="sid in selectedSubs" :key="sid">
                                        <template x-if="subMap[mainId]">
                                            <span class="flex items-center gap-1 px-3 py-1 rounded-full bg-blue-600 text-white text-sm">
                                                <span x-text="(subMap[mainId].find(s => s.id === sid)?.name) || 'Unknown'"></span>
                                                <button type="button" @click="removeSub(sid)" class="ml-1 text-white">&times;</button>
                                            </span>
                                        </template>
                                    </template>
                                </div>

                                {{-- Hidden Inputs --}}
                                <template x-for="sid in selectedSubs" :key="'input-' + sid">
                                    <input type="hidden" name="sub_interest_ids[]" :value="sid">
                                </template>

                                {{-- Add new Sub-Interest --}}
                                <select
                                    @change="if ($event.target.value) { addSub(parseInt($event.target.value)); $event.target.value = ''; }"
                                    class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm"
                                    :disabled="!mainId || !subMap[mainId] || subMap[mainId].length === 0">
                                    <option value="">-- Select a sub-interest to add --</option>
                                    <template x-for="s in (subMap[mainId] || [])" :key="s.id">
                                        <option :value="s.id" x-text="s.name"></option>
                                    </template>
                                </select>

                                @error('sub_interest_ids')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </template>
                    </div>

                    {{-- === Other Preferences (Optional) === --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-6">
                            Other Preferences (Optional)
                        </h3>

                        <div class="mt-3">
                            <label class="block text-sm font-medium">Key</label>
                            <select name="key"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm">
                                <option value="">-- Select a key --</option>
                                @foreach ($allKeys as $key)
                                    <option value="{{ $key }}" {{ old('key') === $key ? 'selected' : '' }}>
                                        {{ ucfirst($key) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('key')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-3">
                            <label class="block text-sm font-medium">Value</label>
                            <input type="text" name="value" value="{{ old('value') }}"
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm">
                            @error('value')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- === Buttons === --}}
                    <div class="pt-4">
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Save Preference
                        </button>
                        <a href="{{ route('traveler.preference-profiles.show', $preferenceProfile) }}"
                           class="ml-2 px-4 py-2 border rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
