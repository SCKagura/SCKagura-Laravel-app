<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-2xl ring-1 ring-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(60%_80%_at_10%_10%,rgba(99,102,241,.20),transparent_60%),radial-gradient(60%_80%_at_90%_30%,rgba(16,185,129,.18),transparent_60%)]"></div>
            <div class="relative flex items-center justify-between p-5 bg-slate-900/70 backdrop-blur">
                <div>
                    <h2 class="font-bold text-2xl tracking-tight text-slate-100">
                        {{ __('Conflicting Emotions') }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-400">
                        {{ __('A concise view of entries with mixed or opposing feelings.') }}
                    </p>
                </div>

                {{-- Summary pill (look ใหม่) --}}
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl
                            text-emerald-200 bg-emerald-400/10 ring-1 ring-inset ring-emerald-300/30 shadow-[inset_0_1px_0_rgba(255,255,255,.06)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M11 2a9 9 0 100 18 9 9 0 000-18zm1 4v6h5v2h-7V6h2z"/>
                    </svg>
                    <span class="text-sm font-medium">{{ __('Total') }}</span>
                    <span class="text-base font-semibold tabular-nums">{{ $total }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Glass card (look ใหม่: dark-first + blur + ring) --}}
            <div class="relative rounded-2xl bg-slate-900/60 backdrop-blur supports-[backdrop-filter]:backdrop-blur
                        ring-1 ring-white/10 shadow-[0_10px_40px_-10px_rgba(0,0,0,.6)]">

                <div class="p-6 sm:p-8">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-xl sm:text-2xl font-semibold text-slate-100">
                            {{ __('Summary') }}
                        </h3>
                        {{-- chip เล็ก ๆ --}}
                        <div class="hidden sm:inline-flex items-center gap-2 text-xs text-slate-300">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-white/5 ring-1 ring-white/10">
                                <span class="size-1.5 rounded-full bg-fuchsia-400"></span>
                                {{ __('Mixed feelings view') }}
                            </span>
                        </div>
                    </div>

                    @if ($conflicts->count() === 0)
                        <div class="p-5 rounded-xl border border-dashed border-slate-700/60
                                    bg-slate-900/40 text-slate-300">
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mt-0.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M11.001 10h2v5h-2v-5zM11 7h2v2h-2V7z"/><path d="M12 2a10 10 0 100 20 10 10 0 000-20zM12 4a8 8 0 110 16 8 8 0 010-16z"/>
                                </svg>
                                <span>{{ __('No conflicting entries found.') }}</span>
                            </div>
                        </div>
                    @else
                        {{-- Mobile: การ์ด (หน้าตาใหม่) --}}
                        <div class="md:hidden space-y-4">
                            @foreach ($conflicts as $row)
                                @php
                                    $intensity = is_numeric($row->intensity ?? null) ? max(0, min(100, (float)$row->intensity)) : null;
                                @endphp

                                <div class="rounded-xl bg-slate-900/70 ring-1 ring-white/10 p-4 shadow-md">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="text-xs text-slate-400">#{{ $row->id }}</div>
                                        <div class="text-xs font-medium text-slate-300">
                                            {{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <p class="text-sm text-slate-100/90 line-clamp-3">
                                            {{ \Illuminate\Support\Str::limit($row->content, 220) }}
                                        </p>
                                    </div>

                                    <div class="mt-4 flex items-center justify-between">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                                     text-indigo-200 bg-indigo-400/10 ring-1 ring-inset ring-indigo-300/30">
                                            <span class="size-1.5 rounded-full bg-indigo-400"></span>
                                            {{ __('Emotion') }}: Sad
                                        </span>

                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[11px] font-medium
                                                     text-rose-200 bg-rose-400/10 ring-1 ring-rose-300/30">
                                            {{ __('Intensity') }}: {{ $intensity !== null ? (int)$intensity : '-' }}
                                        </span>
                                    </div>

                                    <div class="mt-3">
                                        <div class="h-1.5 w-full rounded-full bg-slate-800 overflow-hidden">
                                            <div class="h-full rounded-full bg-gradient-to-r from-rose-500 to-orange-400"
                                                 style="width: {{ $intensity !== null ? $intensity : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Desktop: ตารางโฉมใหม่ (dark zebra + hover soft) --}}
                        <div class="hidden md:block">
                            <div class="overflow-x-auto rounded-xl ring-1 ring-white/10">
                                <table class="min-w-full text-left text-sm text-slate-200">
                                    <thead>
                                        <tr class="bg-slate-900/60">
                                            <th class="py-3.5 px-4 font-semibold text-slate-300">ID</th>
                                            <th class="py-3.5 px-4 font-semibold text-slate-300">{{ __('Date') }}</th>
                                            <th class="py-3.5 px-4 font-semibold text-slate-300">{{ __('Content') }}</th>
                                            <th class="py-3.5 px-4 font-semibold text-slate-300">{{ __('Emotion') }}</th>
                                            <th class="py-3.5 px-4 font-semibold text-slate-300">{{ __('Intensity') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="[&>tr:nth-child(odd)]:bg-white/0 [&>tr:nth-child(even)]:bg-white/5">
                                        @foreach ($conflicts as $row)
                                            @php
                                                $intensity = is_numeric($row->intensity ?? null) ? max(0, min(100, (float)$row->intensity)) : null;
                                            @endphp
                                            <tr class="hover:bg-white/10 transition-colors">
                                                <td class="py-3.5 px-4 align-top text-slate-200 tabular-nums">
                                                    {{ $row->id }}
                                                </td>
                                                <td class="py-3.5 px-4 align-top text-slate-200 whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}
                                                    <div class="mt-0.5 text-xs text-slate-400">
                                                        {{ \Carbon\Carbon::parse($row->date)->diffForHumans() }}
                                                    </div>
                                                </td>
                                                <td class="py-3.5 px-4 align-top">
                                                    <p class="text-slate-100 line-clamp-2">
                                                        {{ \Illuminate\Support\Str::limit($row->content, 160) }}
                                                    </p>
                                                </td>
                                                <td class="py-3.5 px-4 align-top">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                                                 text-indigo-200 bg-indigo-400/10 ring-1 ring-inset ring-indigo-300/30">
                                                        <span class="size-1.5 rounded-full bg-indigo-400"></span>
                                                        Sad
                                                    </span>
                                                </td>
                                                <td class="py-3.5 px-4 align-middle">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-semibold
                                                                  text-rose-200 bg-rose-400/10 ring-1 ring-rose-300/30">
                                                        {{ $row->intensity ?? '-' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6">
                                {{ $conflicts->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
