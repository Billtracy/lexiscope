<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="section-heading">Admin</p>
                <h1 class="text-xl font-bold text-slate-800 dark:text-white mt-0.5">Sections Awaiting Review</h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="badge-ai hidden sm:inline-flex">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path stroke="none"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    Drafts Review
                </span>
                <a href="{{ route('admin.drafts.create') }}" class="btn-primary inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    New Node
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        @if (session('status'))
            <div class="alert-success mb-5">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l3-3z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('status') }}
            </div>
        @endif

        {{-- Search / Filter Bar --}}
        <div class="relative mb-5" x-data="{ q: '' }">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input x-model="q" type="search" placeholder="Filter by section title…"
                class="field-input pl-10 bg-white dark:bg-slate-800"
                @input="
                       document.querySelectorAll('[data-section-card]').forEach(el => {
                           el.style.display = el.dataset.title.toLowerCase().includes($event.target.value.toLowerCase()) ? '' : 'none';
                       });
                   ">
        </div>

        {{-- Section Cards --}}
        @forelse($sections as $section)
            @php
                $chapterNum = $section->parent->number ?? $section->chapter_number;
                $sectionNum = $section->number ?? $section->section_number;
                $title = $section->title ?? ($section->section_title ?? 'Untitled Section');
            @endphp
            <div data-section-card data-title="{{ strtolower($title) }}"
                class="content-card mb-3 group hover:shadow-card-hover hover:-translate-y-0.5 transition-all duration-250">
                <a href="{{ route('admin.drafts.section', $section) }}"
                    class="content-card-body flex items-center gap-4">
                    {{-- Chapter Badge --}}
                    <div
                        class="w-12 h-12 rounded-xl bg-brand-50 dark:bg-brand-900/30 flex flex-col items-center justify-center shrink-0 text-brand-700 dark:text-brand-300">
                        <span class="text-[9px] font-semibold uppercase tracking-wider leading-tight">Ch.</span>
                        <span class="text-lg font-bold leading-tight">{{ $chapterNum ?? '–' }}</span>
                    </div>
                    {{-- Info --}}
                    <div class="min-w-0 flex-1">
                        <p class="font-bold text-slate-800 dark:text-white text-base">
                            Section {{ $sectionNum }}
                        </p>
                        @if ($title && $title !== 'Untitled Section')
                            <p class="text-xs text-slate-400 dark:text-slate-500 font-medium mt-0.5 truncate">
                                {{ $title }}</p>
                        @endif
                    </div>
                    {{-- Arrow --}}
                    <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 shrink-0 group-hover:text-brand-500 transition-colors duration-150"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        @empty
            <div class="empty-state content-card py-20">
                <div
                    class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="font-semibold text-slate-700 dark:text-slate-200">All caught up!</p>
                <p class="text-sm text-slate-400 dark:text-slate-500 mt-1 max-w-xs">
                    No sections are currently awaiting review.
                </p>
            </div>
        @endforelse

    </div>
</x-app-layout>
