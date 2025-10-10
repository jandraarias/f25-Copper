{{-- resources/views/traveler/preferences/profiles/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $preferenceProfile->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-flash-messages />

            <!-- Preferences List -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Preferences</h3>

                @forelse ($preferences as $preference)
                    @php
                        $displayKey = ucfirst($preference->key);
                        $displayValue = $preference->value;

                        if ($preference->key === 'activity' && isset($activityLookup[$preference->value])) {
                            $pair = $activityLookup[$preference->value];
                            $displayValue = "{$pair['main']} → {$pair['sub']}";
                        }
                    @endphp

                    <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 py-2">
                        <div>
                            <span class="font-medium text-gray-900 dark:text-gray-100">
                                {{ $displayKey }}
                            </span>:
                            <span class="text-gray-700 dark:text-gray-300">
                                {{ $displayValue }}
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('traveler.preferences.edit', $preference) }}"
                               class="px-3 py-1.5 border rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('traveler.preferences.destroy', $preference) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 border rounded text-red-600 hover:bg-red-50 dark:hover:bg-red-900"
                                        onclick="return confirm('Delete this preference?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No preferences yet.</p>
                @endforelse

                <div class="mt-4">
                    {{ $preferences->links() }}
                </div>
            </div>

            <!-- Add Preference -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Add Preference</h3>

                <form method="POST"
                      action="{{ route('traveler.preference-profiles.preferences.store', $preferenceProfile) }}"
                      class="space-y-4"
                      x-data="{
                          mainList: @js($mainOptions->map(fn($m) => ['id' => $m->id, 'name' => $m->name])->values()),
                          subMap: @js($subMap),
                          mainId: null,
                          subId: null
                      }"
                      x-init="
                          $watch('mainId', value => {
                              if (subMap[value] && subMap[value].length) subId = subMap[value][0].id;
                              else subId = null;
                          });
                      "
                >
                    @csrf

                    <!-- Main Interest -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Main Interest
                        </label>
                        <select x-model="mainId"
                                name="main_interest_id"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm"
                                required>
                            <option value="">-- Select a main interest --</option>
                            <template x-for="m in mainList" :key="m.id">
                                <option :value="m.id" x-text="m.name"></option>
                            </template>
                        </select>
                        @error('main_interest_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sub Interest -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Sub-Interest
                        </label>
                        <select x-model="subId"
                                name="sub_interest_id"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm"
                                :disabled="!mainId || !subMap[mainId] || subMap[mainId].length === 0"
                                required>
                            <option value="">-- Select a sub-interest --</option>
                            <template x-for="s in (subMap[mainId] || [])" :key="s.id">
                                <option :value="s.id" x-text="s.name"></option>
                            </template>
                        </select>
                        @error('sub_interest_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Add Preference
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
