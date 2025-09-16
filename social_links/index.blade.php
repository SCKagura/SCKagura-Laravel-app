<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Social Media Links') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                <div class="p-6 sm:p-8">

                    <div class="mb-6 text-center">
                        <a href="{{ route('social-links.create') }}"
                           class="inline-flex items-center px-6 py-2.5 rounded-lg font-semibold text-gray-900
                                  bg-gradient-to-r from-sky-400 to-yellow-300 hover:opacity-90 shadow">
                            {{ __('Add New Link') }}
                        </a>
                    </div>

                    @if (session('status'))
                        <div class="mb-4 rounded-lg bg-green-50 dark:bg-green-900/30 text-green-800 dark:text-green-200 px-4 py-3">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($links->isEmpty())
                        <div class="rounded-lg border border-dashed border-gray-300 dark:border-gray-700 p-8 text-center text-gray-600 dark:text-gray-300">
                            {{ __("You don't have any social links yet.") }}
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr class="text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider
                                               bg-gradient-to-r from-indigo-100 to-lime-100 dark:from-indigo-900/40 dark:to-lime-900/20">
                                        <th class="px-6 py-3">{{ __('Platform') }}</th>
                                        <th class="px-6 py-3">{{ __('URL') }}</th>
                                        <th class="px-6 py-3 text-right">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach ($links as $link)
                                        <tr>
                                            <td class="px-6 py-4 font-medium text-gray-800 dark:text-gray-200">
                                                {{ ucwords($link->platform) }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="{{ $link->url }}" target="_blank"
                                                   class="text-indigo-600 dark:text-indigo-400 hover:underline break-all">
                                                    {{ $link->url }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-end gap-2">
                                                    <button
                                                        onclick="window.location.href='{{ route('social-links.edit', $link) }}'"
                                                        class="inline-flex items-center px-4 py-2 rounded-md font-semibold text-white
                                                               bg-amber-500 hover:bg-amber-600">
                                                        {{ __('Edit') }}
                                                    </button>

                                                    <form method="POST" action="{{ route('social-links.destroy', $link) }}"
                                                          onsubmit="return confirm('Delete this link?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="inline-flex items-center px-4 py-2 rounded-md font-semibold text-white
                                                                       bg-red-500 hover:bg-red-600">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
