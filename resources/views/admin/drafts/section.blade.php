<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.drafts.index') }}"
                    class="text-brand-600 dark:text-brand-400 hover:underline font-medium text-sm">
                    Drafts
                </a>
                <svg class="w-4 h-4 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
                <h1 class="font-bold text-slate-800 dark:text-white text-base leading-tight">
                    Section {{ $section->number ?? $section->section_number }}:
                    {{ $section->title ?? $section->section_title }}
                </h1>
            </div>
            <a href="{{ route('admin.drafts.create', ['parent_id' => $section->id]) }}"
                class="btn-primary inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                New Subsection
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-3">

        @forelse($subsections as $draft)
            @php
                $subsecNum = $draft->number ?? $draft->subsection_number;
                $preview = Str::limit($draft->plain_english ?? $draft->legal_text, 100);
                $canEdit = $draft->locked_by === auth()->id() || auth()->user()->role === 'admin';
                $lockedBy = $draft->lockedBy->name ?? 'another contributor';
            @endphp

            <div class="content-card group">
                <div class="content-card-body flex items-start gap-4">

                    {{-- Status indicator bar --}}
                    <div class="shrink-0 flex flex-col items-center gap-2 pt-0.5">
                        <div
                            class="w-1 h-10 rounded-full
                            @if ($draft->status === 'ai_generated') bg-blue-400 dark:bg-blue-500
                            @elseif($draft->status === 'draft')     bg-amber-400 dark:bg-amber-500
                            @else                                   bg-emerald-400 dark:bg-emerald-500 @endif">
                        </div>
                    </div>

                    {{-- Main content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3 flex-wrap mb-1.5">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-slate-800 dark:text-white text-sm">
                                    § {{ $subsecNum }}
                                </span>
                                {{-- Status badge --}}
                                @if ($draft->status === 'ai_generated')
                                    <span class="badge-ai">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                        </svg>
                                        AI Generated
                                    </span>
                                @elseif($draft->status === 'draft')
                                    <span class="badge-draft">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                        Draft
                                    </span>
                                @else
                                    <span class="badge-published"
                                        title="{{ $draft->verifiedBy ? 'Verified by ' . $draft->verifiedBy->name : '' }}">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l3-3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Published
                                        {{ $draft->verifiedBy ? 'by ' . explode(' ', trim($draft->verifiedBy->name))[0] : '' }}
                                    </span>
                                @endif
                                {{-- Locked indicator --}}
                                @if ($draft->status === 'draft' && $draft->locked_by)
                                    <span class="badge-locked">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $canEdit ? 'Locked by you' : 'Locked by ' . $lockedBy }}
                                    </span>
                                @endif
                            </div>

                            {{-- Action --}}
                            <div class="shrink-0">
                                @if ($draft->status === 'published')
                                    <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">Complete</span>
                                @elseif($draft->status === 'ai_generated')
                                    <a href="{{ route('admin.drafts.show', $draft) }}" class="btn-primary btn-sm">
                                        Review
                                    </a>
                                @elseif($draft->status === 'draft')
                                    @if ($canEdit)
                                        <a href="{{ route('admin.drafts.show', $draft) }}" class="btn-primary btn-sm">
                                            Continue
                                        </a>
                                    @else
                                        <span
                                            class="text-xs text-slate-400 dark:text-slate-500 font-medium cursor-not-allowed">Locked</span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2">
                            {{ $preview }}
                        </p>
                    </div>

                </div>
            </div>

        @empty
            <div class="empty-state content-card py-20">
                <div
                    class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <p class="font-semibold text-slate-700 dark:text-slate-200">No subsections found</p>
                <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">This section has no subsections to review.
                </p>
            </div>
        @endforelse

    </div>
</x-app-layout>
