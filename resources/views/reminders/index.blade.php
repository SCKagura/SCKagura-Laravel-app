<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                    {{ __('Reminders') }}
                </h2>
                <p class="text-sm text-gray-400 mt-1">
                    {{ __('Keep track of what matters, right on time.') }}
                </p>
            </div>

            <a href="{{ route('reminders.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold
                      bg-indigo-500/90 hover:bg-indigo-400 text-white shadow-lg shadow-indigo-900/30
                      ring-1 ring-inset ring-white/10 transition">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 5c.55 0 1 .45 1 1v5h5c.55 0 1 .45 1 1s-.45 1-1 1h-5v5c0 .55-.45 1-1 1s-1-.45-1-1v-5H6c-.55 0-1-.45-1-1s.45-1 1-1h5V6c0-.55.45-1 1-1z"/>
                </svg>
                {{ __('New Reminder') }}
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash --}}
            @if (session('status'))
                <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,2500)"
                     class="mb-4 inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                            bg-emerald-500/10 text-emerald-300 ring-1 ring-inset ring-emerald-400/20">
                    {{ session('status') }}
                </div>
            @endif

@php
    /* ---------- badge helpers (dark-first) ---------- */
    $badgeBase = 'inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset';

    $statusClass = fn($s) => match(strtolower($s ?? 'new')){
        'done', 'completed' => "$badgeBase text-emerald-300 ring-emerald-400/30 bg-emerald-500/10",
        'overdue'           => "$badgeBase text-rose-300    ring-rose-400/30    bg-rose-500/10",
        default             => "$badgeBase text-sky-300     ring-sky-400/30     bg-sky-500/10",
    };

    $dot = function ($color) {
        return "<span class='w-1.5 h-1.5 rounded-full {$color}'></span>";
    };

    // แท็ก: ชิปนิ่มๆ โทนมินิมอล
    $tagChip = 'inline-flex items-center select-none gap-1 px-2.5 py-1 rounded-md text-[11px] font-medium
                bg-white/5 text-gray-200 ring-1 ring-inset ring-white/10 hover:bg-white/10 transition';
@endphp

            <div class="rounded-2xl bg-gradient-to-b from-slate-900 to-slate-950 ring-1 ring-white/10 shadow-2xl shadow-black/30 overflow-hidden">
                {{-- toolbar --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-white/10">
                    <div class="flex items-center gap-2">
                        <div class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></div>
                        <span class="text-xs text-gray-400">{{ __('Synced just now') }}</span>
                    </div>

                    {{-- quick filters (ตัวอย่าง, ต่อ API ได้ภายหลัง) --}}
                    <div class="flex items-center gap-2">
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'overdue']) }}"
                           class="px-3 py-1.5 text-xs rounded-lg bg-rose-500/10 text-rose-300 ring-1 ring-inset ring-rose-400/20 hover:bg-rose-500/15">
                            {{ __('Overdue') }}
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'completed']) }}"
                           class="px-3 py-1.5 text-xs rounded-lg bg-emerald-500/10 text-emerald-300 ring-1 ring-inset ring-emerald-400/20 hover:bg-emerald-500/15">
                            {{ __('Completed') }}
                        </a>
                        <a href="{{ route('reminders.index') }}"
                           class="px-3 py-1.5 text-xs rounded-lg bg-white/5 text-gray-300 ring-1 ring-inset ring-white/10 hover:bg-white/10">
                            {{ __('All') }}
                        </a>
                    </div>
                </div>

                {{-- table wrapper --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-gray-200">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wider text-gray-400 bg-white/[0.02]">
                                <th class="px-6 py-3 w-[40%]">{{ __('Title') }}</th>
                                <th class="px-6 py-3 w-[25%]">{{ __('Remind At') }}</th>
                                <th class="px-6 py-3 w-[15%]">{{ __('Status') }}</th>
                                <th class="px-6 py-3 w-[12%]">{{ __('Tags') }}</th>
                                <th class="px-6 py-3 w-[8%] text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-white/5">
                        @forelse ($reminders as $reminder)
                            <tr class="bg-white/[0.01] hover:bg-white/[0.04] transition">
                                <td class="px-6 py-4">
                                    <a href="{{ route('reminders.edit', $reminder) }}"
                                       class="font-medium text-indigo-300 hover:text-indigo-200">
                                        {{ $reminder->title }}
                                    </a>
                                    @if(!empty($reminder->description))
                                        <div class="mt-1 line-clamp-1 text-xs text-gray-400">
                                            {{ Str::limit($reminder->description, 120) }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-gray-300">
                                    @php $dt = \Carbon\Carbon::parse($reminder->remind_at); @endphp
                                    <div class="font-medium">{{ $dt->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</div>
                                    <div class="text-xs text-gray-400">{{ $dt->diffForHumans() }}</div>
                                </td>

                                <td class="px-6 py-4">
                                    @php $s = strtolower($reminder->status ?? 'new'); @endphp
                                    <span class="{{ $statusClass($s) }}">
                                        {!! $s === 'completed' || $s === 'done'
                                            ? $dot('bg-emerald-400')
                                            : ($s === 'overdue' ? $dot('bg-rose-400') : $dot('bg-sky-400')) !!}
                                        {{ ucfirst($s === 'completed' ? 'done' : $s) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    @if($reminder->tags->isNotEmpty())
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($reminder->tags as $tag)
                                                <span class="{{ $tagChip }}">
                                                    <svg class="w-3 h-3 opacity-80" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                        <path d="M21 13.34V7a2 2 0 0 0-2-2h-6.34a2 2 0 0 0-1.41.59L3.59 12.25a2 2 0 0 0 0 2.83l5.33 5.33a2 2 0 0 0 2.83 0l7.66-7.66c.37-.37.59-.88.59-1.41Z"/>
                                                    </svg>
                                                    {{ $tag->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">{{ __('No tags') }}</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('reminders.edit', $reminder) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                                                  bg-white/5 hover:bg-white/10 text-indigo-300 ring-1 ring-inset ring-white/10 transition"
                                           title="{{ __('Edit') }}">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1.003 1.003 0 0 0 0-1.42l-2.34-2.34a1.003 1.003 0 0 0-1.42 0l-1.83 1.83 3.75 3.75 1.84-1.82z"/>
                                            </svg>
                                        </a>

                                        <form method="POST" action="{{ route('reminders.destroy', $reminder) }}"
                                              onsubmit="return confirm('{{ __('Delete this reminder?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                                                           bg-rose-500/10 hover:bg-rose-500/15 text-rose-300
                                                           ring-1 ring-inset ring-rose-400/20 transition"
                                                    title="{{ __('Delete') }}">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                    <path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="mx-auto w-12 h-12 rounded-2xl bg-white/5 ring-1 ring-white/10 flex items-center justify-center text-gray-400">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M12 8a4 4 0 1 0 .001 8.001A4 4 0 0 0 12 8Zm8 .9V19a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8.9l1.45-3.62A2 2 0 0 1 7.32 4h9.36a2 2 0 0 1 1.87 1.28L20 8.9Z"/>
                                        </svg>
                                    </div>
                                    <p class="mt-4 text-gray-400">{{ __('No reminders found.') }}</p>
                                    <a href="{{ route('reminders.create') }}"
                                       class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg
                                              bg-indigo-500/10 text-indigo-300 ring-1 ring-inset ring-indigo-400/20 hover:bg-indigo-500/15">
                                        {{ __('Create your first one') }}
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
