<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Diary') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Add new entry --}}
                    <div class="mb-4">
                        <a href="{{ route('diary.create') }}">
                            <button type="button"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                {{ __('Add New Entry') }}
                            </button>
                        </a>
                    </div>

                    {{-- Empty state --}}
                    @if ($diaryEntries->isEmpty())
                        <div class="p-6 text-center text-gray-600 dark:text-gray-300">
                            {{ __('No diary entries yet.') }}
                        </div>
                    @endif

                    {{-- List entries --}}
                    @foreach ($diaryEntries as $entry)
                        <div class="mb-6 p-6 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-sm">
                            <h3 class="text-xl font-bold mb-2">
                                {{ $entry->date->format('F j, Y') }}
                            </h3>

                            <p class="text-gray-800 dark:text-gray-200">
                                {{ $entry->content }}
                            </p>

                            {{-- Emotions --}}
                            @if ($entry->emotions->isNotEmpty())
                                <h4 class="text-lg font-semibold mt-4 mb-1">Emotions</h4>
                                <ul class="list-disc ms-5 space-y-1">
                                    @foreach ($entry->emotions as $emotion)
                                        <li>
                                            {{ $emotion->name }}
                                            <span class="text-gray-600 dark:text-gray-300">
                                                ({{ __('Intensity') }}: {{ $emotion->pivot->intensity ?? '-' }})
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            {{-- Tags --}}
                            @if ($entry->tags->isNotEmpty())
                                <div class="mt-4">
                                    <h4 class="text-lg font-semibold mb-1">Tags</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($entry->tags as $tag)
                                            <span class="inline-block bg-blue-200 text-blue-800 text-sm px-2 py-1 rounded-full">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Actions --}}
                            <div class="mt-5 flex justify-end gap-2">
                                <button
                                    class="inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-black"
                                    onclick="window.location.href='{{ route('diary.edit', $entry) }}'">
                                    {{ __('Edit') }}
                                </button>

                                <form method="POST" action="{{ route('diary.destroy', $entry) }}"
                                      id="delete-form-{{ $entry->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                                            onclick="confirmDelete({{ $entry->id }})">
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('status') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                });
            @endif
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>
