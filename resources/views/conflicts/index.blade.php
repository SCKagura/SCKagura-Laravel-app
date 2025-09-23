<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Conflicting Emotions') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-4">{{ __('Summary') }}</h3>
                    <div class="inline-flex items-center px-3 py-1 rounded-full
                                bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-100 mb-6">
                        {{ __('Total') }}: <span class="ml-2 font-semibold">{{ $total }}</span>
                    </div>

                    @if ($conflicts->count() === 0)
                        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
                            {{ __('No conflicting entries found.') }}
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th class="py-3 px-4">ID</th>
                                        <th class="py-3 px-4">{{ __('Date') }}</th>
                                        <th class="py-3 px-4">{{ __('Content') }}</th>
                                        <th class="py-3 px-4">{{ __('Emotion') }}</th>
                                        <th class="py-3 px-4">{{ __('Intensity') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($conflicts as $row)
                                        <tr class="border-b border-gray-100 dark:border-gray-700">
                                            <td class="py-3 px-4">{{ $row->id }}</td>
                                            <td class="py-3 px-4">
                                                {{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}
                                            </td>
                                            <td class="py-3 px-4 max-w-3xl">
                                                {{ \Illuminate\Support\Str::limit($row->content, 140) }}
                                            </td>
                                            <td class="py-3 px-4">
                                                <a class="text-indigo-600 dark:text-indigo-400 font-semibold">Sad</a>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full
                                                             bg-rose-100 text-rose-700 dark:bg-rose-900 dark:text-rose-100">
                                                    {{ $row->intensity ?? '-' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $conflicts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
