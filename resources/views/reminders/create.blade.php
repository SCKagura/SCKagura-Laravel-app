<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Reminder') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('reminders.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">{{ __('Title') }}</label>
                            <input type="text" name="title" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100"
                                   value="{{ old('title') }}" required>
                            @error('title')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">{{ __('Note') }}</label>
                            <textarea name="note" rows="3" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100">{{ old('note') }}</textarea>
                            @error('note')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">{{ __('Remind At') }}</label>
                            <input type="datetime-local" name="remind_at"
                                   class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100"
                                   value="{{ old('remind_at') }}" required>
                            @error('remind_at')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-1">{{ __('Status') }}</label>
                            <select name="status" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                                @foreach (['new'=>'New','done'=>'Done','snoozed'=>'Snoozed'] as $v=>$label)
                                    <option value="{{ $v }}" @selected(old('status','new')===$v)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>

                        {{-- Tags --}}
                        <div class="mb-6">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                                <label class="block text-sm font-medium mb-2">{{ __('Select Tags') }}</label>
                                @php $oldTags = (array) old('tags', []); @endphp
                                <div class="flex flex-wrap">
                                    @foreach ($tags as $tag)
                                        <label for="tag_{{ $tag->id }}" class="mr-4 mb-2 inline-flex items-center cursor-pointer">
                                            <input type="checkbox" id="tag_{{ $tag->id }}" name="tags[]"
                                                   value="{{ $tag->id }}" class="h-5 w-5 text-indigo-600 border-gray-300 rounded"
                                                   {{ in_array($tag->id, $oldTags, true) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm">{{ $tag->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('tags')<div class="text-red-500 text-sm mt-2">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">{{ __('Save') }}</button>
                            <a href="{{ route('reminders.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
