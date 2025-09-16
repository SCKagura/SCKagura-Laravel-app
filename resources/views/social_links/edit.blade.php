<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Social Media Link') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('social-links.update', $link) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="platform" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Platform Name') }}
                            </label>
                            <input id="platform" name="platform" type="text"
                                   class="mt-2 block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm
                                          focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                                   value="{{ old('platform', $link->platform) }}" required>
                            @error('platform')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('URL') }}
                            </label>
                            <input id="url" name="url" type="url"
                                   class="mt-2 block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm
                                          focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                                   value="{{ old('url', $link->url) }}" required>
                            @error('url')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit"
                                    class="inline-flex items-center px-5 py-2.5 rounded-lg font-semibold text-white
                                           bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2
                                           focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Update Link') }}
                            </button>

                            <a href="{{ route('social-links.index') }}"
                               class="inline-flex items-center px-5 py-2.5 rounded-lg font-semibold text-gray-700
                                      dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">
                                {{ __('Back to List') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
