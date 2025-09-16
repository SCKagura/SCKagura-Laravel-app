<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Social Link') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ ucfirst($link->platform) }} @if($link->handle) • {{ $link->handle }} @endif
                    </div>
                    <a href="{{ $link->url }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline break-all">
                        {{ $link->url }}
                    </a>

                    <div class="mt-6 flex gap-2">
                        <x-secondary-button onclick="window.location.href='{{ route('social-links.edit', $link) }}'">
                            {{ __('Edit') }}
                        </x-secondary-button>
                        <x-secondary-button onclick="window.location.href='{{ route('social-links.index') }}'">
                            {{ __('Back to List') }}
                        </x-secondary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
