<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Diary Entry') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                    <h1 class="text-2xl font-bold mb-4">{{ __('Hello, ') . Auth::user()->name . '!' }}</h1>
                    <p class="mt-4"><b>{{ __('Update Your Diary Entry') }}</b></p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('diary.update', $diaryEntry) }}">
                        @csrf
                        @method('PUT')

                        {{-- Date --}}
                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                            <input
                                type="date" id="date" name="date"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-100"
                                value="{{ old('date', $diaryEntry->date->format('Y-m-d')) }}" required>
                            @error('date')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Content --}}
                        <div class="mb-6">
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content</label>
                            <textarea id="content" name="content" rows="5"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-100" required>{{ old('content', $diaryEntry->content) }}</textarea>
                            @error('content')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Emotions --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Select Emotions') }}
                            </label>

                            @php
                                $selectedIds = (array) old('emotions', $diaryEntry->emotions->pluck('id')->toArray());
                            @endphp

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach ($emotions as $emotion)
                                    @php
                                        $checked = in_array($emotion->id, $selectedIds, true);
                                        // กัน null เวลาไม่มีความสัมพันธ์อารมณ์นี้มาก่อน
                                        $pivotIntensity = $diaryEntry->emotions->find($emotion->id)?->pivot?->intensity;
                                        $intVal = old('intensity.' . $emotion->id, $pivotIntensity);
                                    @endphp

                                    <label for="emotion_{{ $emotion->id }}"
                                        class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/40 cursor-pointer">
                                        <input type="checkbox"
                                            id="emotion_{{ $emotion->id }}"
                                            name="emotions[]"
                                            value="{{ $emotion->id }}"
                                            class="h-5 w-5 text-indigo-600 rounded border-gray-300"
                                            {{ $checked ? 'checked' : '' }}
                                            onchange="toggleIntensityInput({{ $emotion->id }})">
                                        <span class="font-medium">{{ $emotion->name }}</span>

                                        <div id="intensity_container_{{ $emotion->id }}" class="ml-auto {{ $checked ? '' : 'hidden' }}">
                                            <input type="number"
                                                name="intensity[{{ $emotion->id }}]"
                                                class="w-24 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                                                placeholder="1-10" min="1" max="10"
                                                value="{{ $intVal }}"
                                                {{ $checked ? 'required' : '' }}>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            @error('emotions')
                                <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <x-primary-button>{{ __('Update Entry') }}</x-primary-button>
                        <x-secondary-button type="button" class="ml-2" onclick="window.location.href='{{ url()->previous() }}'">
                            {{ __('Back to Previous') }}
                        </x-secondary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleIntensityInput(emotionId) {
            const checkbox = document.getElementById('emotion_' + emotionId);
            const container = document.getElementById('intensity_container_' + emotionId);
            const input = container.querySelector('input');

            if (checkbox.checked) {
                container.classList.remove('hidden');
                input.setAttribute('required', 'required');
            } else {
                container.classList.add('hidden');
                input.removeAttribute('required');
                input.value = '';
            }
        }
    </script>
</x-app-layout>
