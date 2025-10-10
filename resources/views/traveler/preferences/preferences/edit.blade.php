{{-- resources/views/traveler/preferences/preferences/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Preference') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                {{-- Flash messages --}}
                <x-flash-messages />

                <form method="POST" 
                      action="{{ route('traveler.preferences.update', $preference) }}" 
                      class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- === Activity Preference Section === --}}
                    <div 
                        x-data="{
                            mainList: @js($mainOptions->map(fn($m) => ['id' => $m->id, 'name' => $m->name])->values()),
                            subMap: @js($subMap),
                            mainId: {{ old('main_interest_id', (int)($currentMainId ?? 0)) ?: 'null' }},
                            subId: {{ old('sub_interest_id', (int)($currentSubId ?? 0)) ?: 'null' }},
                        }"
                        x-init="
                            if (!subId && mainId && subMap[mainId] && subMap[mainId].length) {
                                subId = subMap[mainId][0].id
                            }
                            $watch('mainId', value => {
                                if (subMap[value] && subMap[value].length) subId = subMap[value][0].id
                                else subId = null
                            })
                        "
                        class="space-y-6 border-b border-gray-200 dark:border-gray-700 pb-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Activity Preferences
                        </h3>

                        {{-- Main Interest --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Main Interest
                            </label>
                            <select x-model="mainId"
                                    name="main_interest_id"
                                    class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm"
                                    required>
                                <template x-for="m in mainList" :key="m.id">
                                    <option :value="m.id" x-text="m.name"></option>
                                </template>
                            </select>
                            @error('main_interest_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Sub Interest --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Sub-Interest
                            </label>
                            <select x-model="subId"
                                    name="sub_interest_id"
                                    class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm"
                                    :disabled="!mainId || !subMap[mainId] || subMap[mainId].length === 0"
                                    required>
                                <template x-for="s in (subMap[mainId] || [])" :key="s.id">
                                    <option :value="s.id" x-text="s.name"></option>
                                </template>
                            </select>
                            @error('sub_interest_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- === Fallback Legacy Key/Value === --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-6">
                            Other Preferences (Optional)
                        </h3>

                        <div class="mt-3">
                            <label class="block text-sm font-medium">Key</label>
                            <input type="text"
                                   name="key"
                                   value="{{ old('key', $preference->key) }}"
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm">
                            @error('key')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-3">
                            <label class="block text-sm font-medium">Value</label>
                            <input type="text"
                                   name="value"
                                   value="{{ old('value', $preference->value) }}"
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm">
                            @error('value')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- === Buttons === --}}
                    <div class="pt-4">
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Update Preference
                        </button>
                        <a href="{{ route('traveler.preference-profiles.show', $preference->preference_profile_id) }}"
                           class="ml-2 px-4 py-2 border rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
