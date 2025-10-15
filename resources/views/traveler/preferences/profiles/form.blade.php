{{-- resources/views/traveler/preferences/profiles/form.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-200">
                {{ isset($preferenceProfile) ? 'Edit Preference Profile' : 'Create Preference Profile' }}
            </h2>
            <a href="{{ route('traveler.preference-profiles.index') }}"
               class="group flex items-center gap-2 px-4 py-2 rounded-full border border-copper text-copper 
                      hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03]
                      transition-all duration-200 ease-out font-medium shadow-soft dark:shadow-glow-dark">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-0.5"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Profiles
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen transition-colors duration-300" x-data="{ showToast: false }">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <x-flash-messages />

            <div class="bg-white dark:bg-sand-800 text-ink-900 dark:text-ink-200
                        shadow-soft dark:shadow-glow-dark rounded-3xl p-8
                        border border-sand-200 dark:border-ink-700
                        hover:shadow-glow hover:scale-[1.005]
                        transition-all duration-200 ease-out">

                <form 
                    x-data="{ isSubmitting: false }"
                    x-on:submit.prevent="
                        isSubmitting = true;
                        $el.submit();
                        setTimeout(() => { showToast = true }, 600);
                        setTimeout(() => { showToast = false }, 3500);
                    "
                    method="POST"
                    action="{{ isset($preferenceProfile)
                        ? route('traveler.preference-profiles.update', $preferenceProfile)
                        : route('traveler.preference-profiles.store') }}"
                >
                    @csrf
                    @if(isset($preferenceProfile))
                        @method('PUT')
                    @endif

                    {{-- Profile Name --}}
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-ink-700 dark:text-ink-200 mb-2">Profile Name</label>
                        <input type="text" name="name"
                               value="{{ old('name', $preferenceProfile->name ?? '') }}"
                               class="w-full border border-sand-200 dark:border-ink-700
                                      bg-white dark:bg-sand-900 text-ink-900 dark:text-ink-100
                                      rounded-xl shadow-sm focus:ring-copper focus:border-copper
                                      focus:shadow-glow transition-all duration-200"
                               placeholder="My Adventure Preferences" required>
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- === Tab System === --}}
                    <div x-data="{ activeTab: 'activities' }" class="space-y-8">
                        {{-- Tab Navigation --}}
                        <div class="flex justify-center mb-6">
                            <nav class="flex flex-wrap justify-center bg-sand dark:bg-sand-800
                                        rounded-full p-1 shadow-soft dark:shadow-glow-dark transition-colors duration-300">
                                @foreach (['activities' => 'Activities', 'budget' => 'Budget', 'dietary' => 'Dietary', 'accommodation' => 'Accommodation'] as $tab => $label)
                                    <button type="button" 
                                            @click="activeTab = '{{ $tab }}'"
                                            :class="activeTab === '{{ $tab }}'
                                                ? 'bg-gradient-copper text-white shadow-glow scale-[1.05]'
                                                : 'text-ink-700 dark:text-ink-200 hover:text-copper'"
                                            class="px-5 py-2.5 rounded-full text-sm font-medium
                                                   transition-all duration-200 ease-out transform hover:scale-[1.03]">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </nav>
                        </div>

                        {{-- === Activities === --}}
                        <div x-show="activeTab === 'activities'" x-transition.opacity.duration.250ms>
                            <h3 class="text-lg font-semibold text-copper mb-3">Activity Preferences</h3>
                            <p class="text-sm text-ink-500 dark:text-ink-200/70 mb-4">Choose your main interests and specific sub-interests below.</p>

                            <div
                                x-data="{
                                    mainList: @js($mainOptions->map(fn($m) => ['id' => $m->id, 'name' => $m->name])->values()),
                                    subMap: @js($subMap),
                                    mainId: null,
                                    selectedSubs: [],
                                    addSub(id) {
                                        if (!this.selectedSubs.includes(id)) this.selectedSubs.push(id);
                                    },
                                    removeSub(id) {
                                        this.selectedSubs = this.selectedSubs.filter(s => s !== id);
                                    }
                                }"
                                class="space-y-4"
                            >
                                <div>
                                    <label class="block text-sm font-medium text-ink-700 dark:text-ink-200 mb-1">Main Interest</label>
                                    <select x-model="mainId"
                                            class="w-full border border-sand-200 dark:border-ink-700
                                                   bg-white dark:bg-sand-900 text-ink-900 dark:text-ink-100
                                                   rounded-xl shadow-sm focus:ring-copper focus:border-copper transition">
                                        <option value="">-- Select a main interest --</option>
                                        <template x-for="m in mainList" :key="m.id">
                                            <option :value="m.id" x-text="m.name"></option>
                                        </template>
                                    </select>
                                </div>

                                {{-- Sub Interests --}}
                                <div>
                                    <label class="block text-sm font-medium text-ink-700 dark:text-ink-200 mb-2">Sub-Interests</label>
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <template x-for="sub in (mainId ? subMap[mainId] || [] : [])" :key="sub.id">
                                            <button type="button"
                                                    @click="addSub(sub.id)"
                                                    x-show="!selectedSubs.includes(sub.id)"
                                                    class="group px-4 py-1.5 rounded-full border border-copper text-copper 
                                                           hover:bg-copper hover:text-white hover:shadow-glow text-sm font-medium 
                                                           transition-all duration-200 ease-out hover:scale-[1.05]">
                                                <span x-text="sub.name"></span>
                                            </button>
                                        </template>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="id in selectedSubs" :key="'sel-' + id">
                                            <div class="inline-flex items-center px-3 py-1.5 rounded-full
                                                        bg-copper-light text-copper-dark text-sm font-medium
                                                        hover:scale-[1.05] transition-all duration-200 ease-out">
                                                <span x-text="(Object.values(subMap).flat().find(s => s.id === id) || {}).name"></span>
                                                <button type="button" @click="removeSub(id)" class="ml-2 text-copper-dark hover:text-copper transition">&times;</button>
                                                <input type="hidden" name="activities[]" :value="id">
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- === Budget === --}}
                        <div x-show="activeTab === 'budget'" x-transition.opacity.duration.250ms>
                            <h3 class="text-lg font-semibold text-copper mb-3">Budget Preferences</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                @foreach ([['label'=>'Minimum Budget','name'=>'budget_min','placeholder'=>'$0'], ['label'=>'Maximum Budget','name'=>'budget_max','placeholder'=>'$5000']] as $budget)
                                    <div>
                                        <label class="block text-sm font-medium text-ink-700 dark:text-ink-200 mb-1">{{ $budget['label'] }}</label>
                                        <input type="number" name="{{ $budget['name'] }}" min="0"
                                               value="{{ old($budget['name']) ?? ($preferences->where('key', $budget['name'])->first()->value ?? '') }}"
                                               class="w-full border border-sand-200 dark:border-ink-700
                                                      bg-white dark:bg-sand-900 text-ink-900 dark:text-ink-100
                                                      rounded-xl shadow-sm focus:ring-copper focus:border-copper
                                                      focus:shadow-glow transition-all duration-200"
                                               placeholder="{{ $budget['placeholder'] }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- === Dietary === --}}
                        <div x-show="activeTab === 'dietary'" x-transition.opacity.duration.250ms>
                            <h3 class="text-lg font-semibold text-copper mb-3">Dietary Preferences</h3>
                            <p class="text-sm text-ink-500 dark:text-ink-200/70 mb-3">Select all dietary preferences that apply.</p>
                            @php
                                $dietOptions = ['Vegetarian', 'Vegan', 'Pescatarian', 'Gluten-Free', 'Nut-Free', 'Dairy-Free'];
                            @endphp
                            <div class="flex flex-wrap gap-2">
                                @foreach ($dietOptions as $diet)
                                    <label class="group inline-flex items-center px-4 py-1.5 rounded-full border border-copper text-copper 
                                                   hover:bg-copper hover:text-white text-sm font-medium cursor-pointer shadow-sm
                                                   hover:shadow-glow hover:scale-[1.05] transition-all duration-200 ease-out">
                                        <input type="checkbox" name="dietary[]" value="{{ $diet }}" class="hidden peer"
                                               @checked(in_array($diet, old('dietary', $preferences->where('key', 'dietary')->pluck('value')->toArray() ?? [])))>
                                        <span>{{ $diet }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- === Accommodation === --}}
                        <div x-show="activeTab === 'accommodation'" x-transition.opacity.duration.250ms>
                            <h3 class="text-lg font-semibold text-copper mb-3">Accommodation Preferences</h3>
                            <p class="text-sm text-ink-500 dark:text-ink-200/70 mb-3">Select the types of lodging you prefer while traveling.</p>
                            @php
                                $accomOptions = ['Hotel', 'Motel', 'Airbnb', 'Hostel', 'Resort', 'Guesthouse'];
                            @endphp
                            <div class="flex flex-wrap gap-2">
                                @foreach ($accomOptions as $acc)
                                    <label class="group inline-flex items-center px-4 py-1.5 rounded-full border border-copper text-copper 
                                                   hover:bg-copper hover:text-white text-sm font-medium cursor-pointer shadow-sm
                                                   hover:shadow-glow hover:scale-[1.05] transition-all duration-200 ease-out">
                                        <input type="checkbox" name="accommodation[]" value="{{ $acc }}" class="hidden peer"
                                               @checked(in_array($acc, old('accommodation', $preferences->where('key', 'accommodation')->pluck('value')->toArray() ?? [])))>
                                        <span>{{ $acc }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- === Buttons === --}}
                    <div class="mt-10 flex justify-end gap-4">
                        <a href="{{ route('traveler.preference-profiles.index') }}"
                           class="group px-6 py-2.5 rounded-full border border-ink-500 dark:border-ink-700
                                  text-ink-700 dark:text-ink-200 hover:text-copper hover:border-copper
                                  hover:scale-[1.03] hover:shadow-glow transition-all duration-200 ease-out
                                  font-medium shadow-soft dark:shadow-glow-dark">
                            Cancel
                        </a>
                        <button type="submit"
                                :disabled="isSubmitting"
                                class="group flex items-center justify-center gap-2 px-8 py-2.5 rounded-full
                                       bg-gradient-copper dark:bg-gradient-copper-dark text-white font-semibold
                                       shadow-soft hover:shadow-glow hover:scale-[1.03]
                                       transition-all duration-200 ease-out disabled:opacity-80 disabled:cursor-not-allowed">
                            <template x-if="!isSubmitting">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform duration-200 group-hover:rotate-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </template>
                            <template x-if="isSubmitting">
                                <svg class="animate-spin w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-30" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
                                    <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v3a5 5 0 00-5 5H4z"/>
                                </svg>
                            </template>
                            <span x-text="isSubmitting ? 'Saving...' : '{{ isset($preferenceProfile) ? 'Save Changes' : 'Create Profile' }}'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ✨ Toast Notification --}}
        <div x-show="showToast"
             x-transition.opacity.duration.500ms
             class="fixed bottom-6 right-6 bg-gradient-copper dark:bg-gradient-copper-dark
                    text-white px-5 py-3 rounded-xl shadow-glow text-sm font-medium
                    flex items-center gap-2 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <span>Profile saved successfully!</span>
        </div>
    </div>
</x-app-layout>
