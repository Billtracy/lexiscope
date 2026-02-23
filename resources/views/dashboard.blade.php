<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="section-heading">Welcome back</p>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white mt-0.5">
                    {{ Auth::user()->name }}
                </h1>
            </div>
            <span class="{{ Auth::user()->role === 'admin' ? 'badge-admin' : 'badge-student' }}">
                @if (Auth::user()->role === 'admin')
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    Admin
                @else
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                    </svg>
                    Law Student
                @endif
            </span>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- ── Stats Row ──────────────────────────────── --}}
        <div>
            <p class="section-heading mb-3">Overview</p>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-3">

                <div class="stat-card col-span-2 sm:col-span-1">
                    <div class="flex items-start gap-3">
                        <div class="stat-card-icon bg-brand-50 dark:bg-brand-900/30">
                            <svg class="w-5 h-5 text-brand-600 dark:text-brand-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stats['published'] ?? 0 }}
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-tight">
                                Sections<br>Published</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="flex items-start gap-3">
                        <div class="stat-card-icon bg-amber-50 dark:bg-amber-900/30">
                            <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-2xl font-bold text-slate-800 dark:text-white">
                                {{ $stats['in_progress'] ?? 0 }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-tight">In<br>Progress
                            </p>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="flex items-start gap-3">
                        <div class="stat-card-icon bg-blue-50 dark:bg-blue-900/30">
                            <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-2xl font-bold text-slate-800 dark:text-white">
                                {{ $stats['ai_generated'] ?? 0 }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-tight">AI<br>Generated
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Quick Actions ───────────────────────────── --}}
        <div>
            <p class="section-heading mb-3">Quick Actions</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                <a href="{{ route('admin.drafts.index') }}" class="action-card group">
                    <div
                        class="w-12 h-12 rounded-2xl bg-brand-600 flex items-center justify-center shrink-0
                                group-hover:bg-brand-700 transition-colors duration-150 shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-800 dark:text-white text-sm">Review Drafts</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Browse sections awaiting review</p>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 ml-auto shrink-0
                                group-hover:text-brand-500 transition-colors duration-150"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                @if (Auth::user()->role === 'admin')
                    <a href="{{ route('admin.users.index') }}" class="action-card group">
                        <div
                            class="w-12 h-12 rounded-2xl bg-violet-600 flex items-center justify-center shrink-0
                                group-hover:bg-violet-700 transition-colors duration-150 shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-800 dark:text-white text-sm">Manage Contributors</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Add, edit or remove law
                                students</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 ml-auto shrink-0
                                group-hover:text-violet-500 transition-colors duration-150"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <div class="action-card opacity-60 cursor-default select-none">
                        <div
                            class="w-12 h-12 rounded-2xl bg-slate-200 dark:bg-slate-700 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-800 dark:text-white text-sm">My Profile</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">View and update your account
                            </p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="ml-auto shrink-0">
                            <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                @endif

            </div>
        </div>

        {{-- ── Getting Started Banner (if no stats yet) ── --}}
        @if (($stats['published'] ?? 0) === 0 && ($stats['in_progress'] ?? 0) === 0)
            <div class="content-card">
                <div class="content-card-body">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 dark:text-white text-sm">Get started by reviewing a
                                section</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                Sections generated by AI are waiting for your expert review. Head to <strong>Drafts
                                    Review</strong> to begin.
                            </p>
                            <a href="{{ route('admin.drafts.index') }}" class="btn-primary btn-sm mt-3 inline-flex">
                                Go to Drafts →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ── Leaderboard ──────────────────────────────── --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <p class="section-heading">🏆 Top Contributors</p>
                <span class="text-xs text-slate-400 dark:text-slate-500">by published nodes</span>
            </div>

            <div class="content-card overflow-hidden">
                @if ($leaderboard->isEmpty())
                    {{-- Empty state --}}
                    <div class="content-card-body py-10 text-center">
                        <div
                            class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center mx-auto mb-3">
                            <span class="text-2xl">🏅</span>
                        </div>
                        <p class="font-semibold text-slate-700 dark:text-slate-300 text-sm">No rankings yet</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 max-w-xs mx-auto">
                            Be the first on the board — review a draft and hit <strong>Verify &amp; Publish</strong>.
                        </p>
                        <a href="{{ route('admin.drafts.index') }}" class="btn-primary btn-sm mt-4 inline-flex">
                            Start Reviewing →
                        </a>
                    </div>
                @else
                    {{-- Header row --}}
                    <div
                        class="grid grid-cols-[2rem_1fr_auto] items-center gap-3 px-5 py-2.5
                                text-xs font-semibold uppercase tracking-wider
                                text-slate-400 dark:text-slate-500
                                border-b border-slate-100 dark:border-slate-700/50">
                        <span>#</span>
                        <span>Contributor</span>
                        <span class="text-right">Nodes</span>
                    </div>

                    @php $maxCount = $leaderboard->first()->published_count; @endphp

                    <ul class="divide-y divide-slate-50 dark:divide-slate-700/40">
                        @foreach ($leaderboard as $rank => $contributor)
                            @php
                                $isMe = $contributor->id === Auth::id();
                                $initials = collect(explode(' ', $contributor->name))
                                    ->map(fn($w) => strtoupper($w[0] ?? ''))
                                    ->take(2)
                                    ->implode('');
                                $colors = [
                                    'bg-rose-500',
                                    'bg-orange-500',
                                    'bg-amber-500',
                                    'bg-emerald-500',
                                    'bg-sky-500',
                                    'bg-violet-500',
                                    'bg-pink-500',
                                    'bg-teal-500',
                                    'bg-indigo-500',
                                    'bg-fuchsia-500',
                                ];
                                $color = $colors[abs(crc32($contributor->name)) % count($colors)];
                                $pct = $maxCount > 0 ? round(($contributor->published_count / $maxCount) * 100) : 0;
                            @endphp
                            <li
                                class="grid grid-cols-[2rem_1fr_auto] items-center gap-3 px-5 py-3
                                        {{ $isMe ? 'bg-brand-50/70 dark:bg-brand-900/10' : 'hover:bg-slate-50 dark:hover:bg-slate-800/40' }}
                                        transition-colors duration-150">

                                {{-- Rank --}}
                                <div class="text-center">
                                    @if ($rank === 0)
                                        <span class="text-lg">🥇</span>
                                    @elseif ($rank === 1)
                                        <span class="text-lg">🥈</span>
                                    @elseif ($rank === 2)
                                        <span class="text-lg">🥉</span>
                                    @else
                                        <span
                                            class="text-sm font-bold text-slate-400 dark:text-slate-500">{{ $rank + 1 }}</span>
                                    @endif
                                </div>

                                {{-- Contributor info + bar --}}
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2.5 mb-1.5">
                                        <div
                                            class="w-7 h-7 rounded-full {{ $color }} flex items-center justify-center
                                                    text-white text-xs font-bold shrink-0 select-none">
                                            {{ $initials }}
                                        </div>
                                        <div class="min-w-0 flex items-center gap-1.5 flex-wrap">
                                            <span
                                                class="font-semibold text-sm text-slate-800 dark:text-white truncate">
                                                {{ $contributor->name }}
                                            </span>
                                            @if ($isMe)
                                                <span
                                                    class="inline-flex text-[10px] font-bold uppercase tracking-wide
                                                             px-1.5 py-0.5 rounded-full
                                                             bg-brand-100 dark:bg-brand-900/40
                                                             text-brand-700 dark:text-brand-400">you</span>
                                            @endif
                                            <span
                                                class="{{ $contributor->role === 'admin' ? 'badge-admin' : 'badge-student' }} text-[10px] py-0 px-1.5">
                                                {{ ucfirst($contributor->role) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="h-1.5 rounded-full bg-slate-100 dark:bg-slate-700/50 overflow-hidden">
                                        <div class="h-full rounded-full
                                                    {{ $rank === 0 ? 'bg-gradient-to-r from-amber-400 to-yellow-500' : 'bg-brand-500 dark:bg-brand-400' }}
                                                    transition-all duration-700"
                                            style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>

                                {{-- Count --}}
                                <div class="text-right">
                                    <span
                                        class="text-lg font-bold {{ $rank === 0 ? 'text-amber-500' : 'text-slate-700 dark:text-slate-200' }}">
                                        {{ $contributor->published_count }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>
