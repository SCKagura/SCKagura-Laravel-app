<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reminders') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl">
                <div class="p-6">

                    {{-- Flash message --}}
                    @if (session('status'))
                        <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,2500)"
                             class="mb-4 inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                                    bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Create button --}}
                    <div class="mb-5">
                        <a href="{{ route('reminders.create') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-white
                                  bg-indigo-600 hover:bg-indigo-500 shadow focus:outline-none
                                  focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="text-lg leading-none">+</span>
                            {{ __('New Reminder') }}
                        </a>
                    </div>

@php
    /* ---------- badge helpers ---------- */
    $badgeBase = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border';

    // สถานะ (เส้นขอบ + พื้นหลังอ่อน / โหมดมืดโปร่งใส)
    $statusClass = fn($s) => match(strtolower($s ?? 'new')){
        'done', 'completed' => "$badgeBase border-emerald-300 text-emerald-700 bg-emerald-50
                                dark:border-emerald-700 dark:text-emerald-200 dark:bg-transparent",
        'overdue'           => "$badgeBase border-rose-300    text-rose-700    bg-rose-50
                                dark:border-rose-700    dark:text-rose-200    dark:bg-transparent",
        default             => "$badgeBase border-blue-300    text-blue-700    bg-blue-50
                                dark:border-blue-700    dark:text-blue-200    dark:bg-transparent",
    };

    // แท็ก: ตัวอักษรสีขาว + กรอบสี่เหลี่ยมสีดำ
    $tagColor = 'inline-flex items-center px-2.5 py-1 text-xs font-semibold
                 border-2 border-black text-white bg-black/90 rounded-md
                 dark:border-black dark:text-white dark:bg-black';
                 // --- Tag chip: สี่เหลี่ยม + เส้นขอบ บาง ๆ โทนสุภาพ ---
    $tagChip = 'inline-flex items-center select-none px-3 py-1 text-xs font-semibold rounded-md
            text-white bg-emerald-600 ring-1 ring-inset ring-emerald-500/50
            hover:bg-emerald-500 transition-colors
            dark:bg-emerald-500 dark:ring-emerald-400/50 dark:hover:bg-emerald-400';
        @endphp

                    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            {{-- หัวตารางสีเดียวกับพื้นหลัง แยกด้วย divide --}}
                            <thead class="bg-white dark:bg-gray-800">
                                <tr class="text-left text-sm font-semibold text-gray-600 dark:text-gray-300">
                                    <th class="px-6 py-3 w-[35%]">{{ __('Title') }}</th>
                                    <th class="px-6 py-3 w-[25%]">{{ __('Remind At') }}</th>
                                    <th class="px-6 py-3 w-[15%]">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 w-[15%]">{{ __('Tags') }}</th>
                                    <th class="px-6 py-3 w-[10%] text-right">{{ __('Actions') }}</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($reminders as $reminder)
                                <tr class="bg-white dark:bg-gray-800 text-sm text-gray-800 dark:text-gray-200">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('reminders.edit', $reminder) }}"
                                           class="font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-300 dark:hover:text-indigo-200">
                                            {{ $reminder->title }}
                                        </a>
                                    </td>

                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($reminder->remind_at)->format('Y-m-d H:i') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="{{ $statusClass($reminder->status) }}">
                                            {{ ucfirst(strtolower($reminder->status ?? 'New')) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
    @if($reminder->tags->isNotEmpty())
        <div class="flex flex-wrap gap-2">
            @foreach ($reminder->tags as $tag)
                <span class="{{ $tagChip }}">{{ $tag->name }}</span>
            @endforeach
        </div>
    @else
        <span class="text-xs text-gray-400 dark:text-gray-500">{{ __('No tags') }}</span>
    @endif
</td>


                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-4">
                                            <a href="{{ route('reminders.edit', $reminder) }}"
                                               class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-300 dark:hover:text-indigo-200">
                                                {{ __('Edit') }}
                                            </a>

                                            <form method="POST" action="{{ route('reminders.destroy', $reminder) }}"
                                                  onsubmit="return confirm('{{ __('Delete this reminder?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="font-semibold text-red-600 hover:text-red-700
                                                               dark:text-rose-300 dark:hover:text-rose-200">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-white dark:bg-gray-800">
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                        {{ __('No reminders found.') }}
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
