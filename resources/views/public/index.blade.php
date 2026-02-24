<x-public-layout>
    <div x-data="{
        mobileMenuOpen: false,
        slideoverOpen: false,
        activeNode: null,
        openSlideover(node) {
            this.activeNode = node;
            this.slideoverOpen = true;
        }
    }" class="flex h-screen w-full overflow-hidden">

        {{-- ═══════════════════════════════════════
             LEFT SIDEBAR
        ═══════════════════════════════════════ --}}
        {{-- Mobile overlay --}}
        <div x-show="mobileMenuOpen" x-transition.opacity @click="mobileMenuOpen = false"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-10 md:hidden" x-cloak>
        </div>

        <aside
            class="w-72 flex-shrink-0 flex flex-col z-20
                      bg-white dark:bg-slate-900
                      border-r border-slate-100 dark:border-slate-800
                      absolute md:relative h-full md:h-auto
                      transition-transform duration-300 md:translate-x-0"
            :class="mobileMenuOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full'">

            {{-- Brand --}}
            <div class="h-16 flex items-center gap-3 px-5 border-b border-slate-100 dark:border-slate-800 shrink-0">
                <img src="{{ asset('logo.png') }}" class="w-8 h-8 rounded-xl shadow-sm object-cover shrink-0"
                    alt="Lexiscope Logo" />
                <div class="min-w-0">
                    <p class="font-bold text-slate-900 dark:text-white text-base leading-tight tracking-tight">Lexiscope
                    </p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 leading-tight">Interactive Constitution</p>
                </div>
            </div>

            {{-- Chapter navigation --}}
            <nav class="flex-1 overflow-y-auto custom-scroll py-4 px-3 space-y-0.5">
                <p class="px-3 pb-2 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                    Chapters</p>

                @foreach ($chapters as $chapter)
                    <div x-data="{ open: false }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-xl
                                       text-slate-700 dark:text-slate-300
                                       hover:bg-slate-100 dark:hover:bg-slate-800
                                       focus:outline-none transition-colors duration-150">
                            <span class="flex items-center gap-2.5">
                                <span
                                    class="flex items-center justify-center w-6 h-6 rounded-lg
                                             bg-brand-100 dark:bg-brand-900/30
                                             text-brand-700 dark:text-brand-400
                                             text-xs font-bold shrink-0">
                                    {{ $chapter->chapter_number }}
                                </span>
                                <span
                                    class="truncate">{{ Str::limit($chapter->chapter_title ?? 'Chapter ' . $chapter->chapter_number, 28) }}</span>
                            </span>
                            <svg class="h-3.5 w-3.5 text-slate-400 shrink-0 transition-transform duration-200"
                                :class="open ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <div x-show="open" x-collapse class="pl-4 pt-0.5 pb-1.5 space-y-0.5">
                            @foreach ($chapter->children as $section)
                                <a href="#section-{{ $section->id }}" @click="mobileMenuOpen = false"
                                    class="flex items-center gap-2 px-3 py-1.5 text-sm rounded-lg
                                          text-slate-600 dark:text-slate-400
                                          hover:text-brand-700 dark:hover:text-brand-400
                                          hover:bg-brand-50 dark:hover:bg-brand-900/20
                                          transition-colors duration-150">
                                    <span
                                        class="text-xs text-slate-400 dark:text-slate-600 font-mono font-bold shrink-0">§
                                        {{ $section->section_number }}</span>
                                    @if ($section->section_title && $section->section_title !== 'Untitled Section')
                                        <span
                                            class="truncate text-xs">{{ Str::limit($section->section_title, 30) }}</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </nav>

            {{-- Sidebar footer --}}
            <div class="p-4 border-t border-slate-100 dark:border-slate-800 shrink-0 flex items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="flex-1 btn-primary btn-sm text-center text-xs">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="flex-1 btn-primary btn-sm text-center text-xs">
                        Contributor Login
                    </a>
                @endauth

                {{-- Dark mode toggle --}}
                <button onclick="toggleDarkMode()"
                    class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0
                               bg-slate-100 dark:bg-slate-800
                               text-slate-500 dark:text-slate-400
                               hover:bg-slate-200 dark:hover:bg-slate-700
                               transition-colors duration-150"
                    title="Toggle dark mode">
                    <svg class="w-3.5 h-3.5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg class="w-3.5 h-3.5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
            </div>
        </aside>

        {{-- ═══════════════════════════════════════
             MAIN CONTENT
        ═══════════════════════════════════════ --}}
        <main class="flex-1 flex flex-col h-full overflow-hidden relative">

            {{-- Mobile header --}}
            <header
                class="h-16 md:hidden flex items-center justify-between px-4
                           border-b border-slate-100 dark:border-slate-800
                           bg-white/90 dark:bg-slate-900/90 backdrop-blur-md sticky top-0 z-10 shrink-0">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('logo.png') }}" class="w-7 h-7 rounded-lg shadow-sm object-cover"
                        alt="Lexiscope Logo" />
                    <span class="font-bold text-base text-slate-900 dark:text-white">Lexiscope</span>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Dark mode toggle (mobile) --}}
                    <button onclick="toggleDarkMode()"
                        class="w-8 h-8 rounded-lg flex items-center justify-center
                                   bg-slate-100 dark:bg-slate-800
                                   text-slate-500 dark:text-slate-400">
                        <svg class="w-3.5 h-3.5 hidden dark:block" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg class="w-3.5 h-3.5 block dark:hidden" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="w-8 h-8 rounded-lg flex items-center justify-center
                                   bg-slate-100 dark:bg-slate-800
                                   text-slate-500 dark:text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </header>

            {{-- Scrollable content --}}
            <div class="flex-1 overflow-y-auto custom-scroll scroll-smooth">
                <div class="max-w-4xl mx-auto px-5 py-12 md:py-20 lg:px-10">

                    {{-- Hero heading --}}
                    <div class="mb-16 text-center">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full
                                    bg-brand-50 dark:bg-brand-900/30
                                    border border-brand-200 dark:border-brand-700
                                    text-brand-700 dark:text-brand-400
                                    text-xs font-semibold uppercase tracking-wider mb-5">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l3-3z"
                                    clip-rule="evenodd" />
                            </svg>
                            Nigeria • 1999 Constitution
                        </div>
                        <h1
                            class="font-serif text-4xl md:text-6xl font-bold mb-4
                                   text-slate-900 dark:text-white leading-tight">
                            The Interactive<br>
                            <span
                                class="bg-gradient-to-r from-brand-600 via-violet-600 to-brand-500
                                         bg-clip-text text-transparent">Constitution</span>
                        </h1>
                        <p class="text-slate-500 dark:text-slate-400 text-lg max-w-lg mx-auto leading-relaxed">
                            Explore constitutional provisions with plain-English explanations, case law references, and
                            global comparisons.
                        </p>
                    </div>

                    {{-- Preamble --}}
                    <div class="mb-12 md:mb-20 max-w-2xl mx-auto px-2 md:px-0">
                        <div
                            class="flex items-center gap-2 mb-3 md:mb-4
                                    text-brand-700 dark:text-brand-400 text-xs font-semibold uppercase tracking-wider">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                            Preamble
                        </div>
                        <blockquote
                            class="relative pl-4 md:pl-6 border-l-[3px] md:border-l-4 border-brand-400 dark:border-brand-600
                                          font-serif text-slate-700 dark:text-slate-300 leading-relaxed text-sm md:text-[15px] space-y-3 md:space-y-4">
                            <p class="font-semibold text-slate-900 dark:text-white text-[15px] md:text-base">
                                Constitution of the Federal Republic of Nigeria 1999
                            </p>
                            <p>
                                We the people of the Federal Republic of Nigeria
                            </p>
                            <p>
                                Having firmly and solemnly resolve, to live in unity and harmony as one indivisible
                                and indissoluble sovereign nation under God, dedicated to the promotion of
                                inter-African solidarity, world peace, international co-operation and understanding
                            </p>
                            <p>
                                And to provide for a Constitution for the purpose of promoting the good government
                                and welfare of all persons in our country, on the principles of freedom, equality
                                and justice, and for the purpose of consolidating the unity of our people
                            </p>
                            <p class="font-semibold text-slate-900 dark:text-white">
                                Do hereby make, enact and give to ourselves the following Constitution:&mdash;
                            </p>
                        </blockquote>
                    </div>

                    {{-- Chapters & Sections --}}
                    <div class="space-y-24">
                        @foreach ($chapters as $chapter)
                            <div>
                                {{-- Chapter heading --}}
                                <div class="flex items-center gap-4 mb-10">
                                    <div
                                        class="flex items-center justify-center w-12 h-12 rounded-2xl
                                                bg-gradient-to-br from-brand-500 to-violet-600
                                                text-white font-bold text-lg shadow-lg shrink-0">
                                        {{ $chapter->chapter_number }}
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5">
                                            Chapter {{ $chapter->chapter_number }}</p>
                                        <h2
                                            class="font-serif text-2xl md:text-3xl font-semibold text-slate-900 dark:text-white truncate">
                                            {{ $chapter->chapter_title }}
                                        </h2>
                                    </div>
                                </div>

                                <div class="space-y-12">
                                    @foreach ($chapter->children as $section)
                                        <article id="section-{{ $section->id }}" class="scroll-mt-8 group">
                                            {{-- Section title bar --}}
                                            <div class="flex items-start justify-between mb-5 gap-3">
                                                <h3
                                                    class="font-serif text-xl md:text-2xl font-medium text-slate-900 dark:text-white flex items-center gap-3 flex-wrap">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-1 rounded-lg
                                                                 bg-slate-100 dark:bg-slate-800
                                                                 text-slate-600 dark:text-slate-300
                                                                 text-sm font-sans font-bold tracking-wide shrink-0">
                                                        § {{ $section->section_number }}
                                                    </span>
                                                    @if ($section->section_title && $section->section_title !== 'Untitled Section')
                                                        {{ $section->section_title }}
                                                    @endif
                                                    @if ($section->verifiedBy)
                                                        <span
                                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-sans font-semibold uppercase tracking-wider bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800 shrink-0">
                                                            <svg class="w-2.5 h-2.5" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor"
                                                                stroke-width="2.5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Verified by
                                                            {{ explode(' ', trim($section->verifiedBy->name))[0] }}
                                                        </span>
                                                    @endif
                                                </h3>

                                                @if ($section->caseLaws->isNotEmpty() || $section->internationalComparisons->isNotEmpty())
                                                    <button @click="openSlideover({{ $section->toJson() }})"
                                                        class="shrink-0 opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1.5
                                                                   text-xs font-semibold
                                                                   text-brand-700 dark:text-brand-400
                                                                   bg-brand-50 dark:bg-brand-900/30
                                                                   hover:bg-brand-100 dark:hover:bg-brand-900/50
                                                                   border border-brand-200 dark:border-brand-700
                                                                   px-3 py-1.5 rounded-full transition-colors duration-150">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        View Insights
                                                    </button>
                                                @endif
                                            </div>

                                            {{-- Render Section Legal Text if there are no subsections, or if the text is substantial --}}
                                            @if ($section->children->isEmpty() && $section->legal_text && $section->legal_text !== $section->section_title)
                                                <div
                                                    class="prose prose-slate dark:prose-invert max-w-none font-serif text-slate-700 dark:text-slate-300 leading-relaxed text-[15px]">
                                                    <p>{{ $section->legal_text }}</p>
                                                </div>

                                                @if ($section->plain_english)
                                                    <div
                                                        class="mt-5 p-5 rounded-2xl bg-brand-50/60 dark:bg-brand-900/10 border border-brand-200/60 dark:border-brand-700/30">
                                                        <div
                                                            class="flex items-center gap-2 mb-2.5 text-brand-700 dark:text-brand-400 font-semibold text-xs uppercase tracking-wider">
                                                            <svg class="w-3.5 h-3.5" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor"
                                                                stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                            </svg>
                                                            Plain English
                                                        </div>
                                                        <p
                                                            class="text-slate-700 dark:text-slate-300 text-sm leading-relaxed">
                                                            {{ $section->plain_english }}</p>
                                                    </div>
                                                @endif

                                                @if ($section->keywords && count($section->keywords) > 0)
                                                    <div class="mt-4 flex flex-wrap gap-2">
                                                        @foreach ($section->keywords as $keyword)
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                                                                {{ $keyword }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endif

                                            {{-- Render Subsections --}}
                                            @if ($section->children->isNotEmpty())
                                                <div class="mt-6 space-y-8">
                                                    @foreach ($section->children as $subsection)
                                                        <div
                                                            class="group/sub flex flex-col md:flex-row gap-4 md:gap-6 pl-2 md:pl-4 border-l-2 border-slate-100 dark:border-slate-800 hover:border-brand-300 dark:hover:border-brand-700 transition-colors">

                                                            {{-- Subsection number and badges --}}
                                                            <div
                                                                class="shrink-0 flex items-center md:items-start gap-3 md:w-16 pt-1">
                                                                <span
                                                                    class="font-bold font-serif text-slate-900 dark:text-white text-lg">
                                                                    {{ $subsection->subsection_number }}
                                                                </span>

                                                                @if ($subsection->verifiedBy)
                                                                    <span
                                                                        title="Verified by {{ $subsection->verifiedBy->name }}"
                                                                        class="md:hidden inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-sans font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800">
                                                                        <svg class="w-2 h-2" fill="none"
                                                                            viewBox="0 0 24 24" stroke="currentColor"
                                                                            stroke-width="3">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                d="M5 13l4 4L19 7" />
                                                                        </svg>
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            {{-- Subsection content --}}
                                                            <div class="flex-1 min-w-0">
                                                                <div
                                                                    class="prose prose-slate dark:prose-invert max-w-none font-serif text-slate-700 dark:text-slate-300 leading-relaxed text-[15px]">
                                                                    <p>{{ $subsection->legal_text }}</p>
                                                                </div>

                                                                @if ($subsection->plain_english)
                                                                    <div
                                                                        class="mt-4 p-4 rounded-xl bg-brand-50/40 dark:bg-brand-900/10 border border-brand-100 dark:border-brand-700/30">
                                                                        <div
                                                                            class="flex items-center gap-2 mb-2 text-brand-700 dark:text-brand-400 font-semibold text-[11px] uppercase tracking-wider">
                                                                            <svg class="w-3 h-3" fill="none"
                                                                                viewBox="0 0 24 24"
                                                                                stroke="currentColor"
                                                                                stroke-width="2">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                                            </svg>
                                                                            Plain English
                                                                        </div>
                                                                        <p
                                                                            class="text-slate-600 dark:text-slate-400 text-[13px] leading-relaxed">
                                                                            {{ $subsection->plain_english }}</p>
                                                                    </div>
                                                                @endif

                                                                <div class="mt-3 flex items-center flex-wrap gap-2">
                                                                    @if ($subsection->verifiedBy)
                                                                        <span
                                                                            class="hidden md:inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-sans font-semibold uppercase tracking-wider bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800">
                                                                            <svg class="w-2.5 h-2.5" fill="none"
                                                                                viewBox="0 0 24 24"
                                                                                stroke="currentColor"
                                                                                stroke-width="2.5">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    d="M5 13l4 4L19 7" />
                                                                            </svg>
                                                                            Verified by
                                                                            {{ explode(' ', trim($subsection->verifiedBy->name))[0] }}
                                                                        </span>
                                                                    @endif

                                                                    @if ($subsection->keywords && count($subsection->keywords) > 0)
                                                                        @foreach ($subsection->keywords as $keyword)
                                                                            <span
                                                                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                                                                                {{ $keyword }}
                                                                            </span>
                                                                        @endforeach
                                                                    @endif

                                                                    @if ($subsection->caseLaws->isNotEmpty() || $subsection->internationalComparisons->isNotEmpty())
                                                                        <button
                                                                            @click="openSlideover({{ $subsection->toJson() }})"
                                                                            class="ml-auto opacity-0 group-hover/sub:opacity-100 transition-opacity flex items-center gap-1.5 text-[11px] font-semibold text-brand-600 dark:text-brand-400 hover:text-brand-800 dark:hover:text-brand-300">
                                                                            <svg class="w-3 h-3" fill="none"
                                                                                viewBox="0 0 24 24"
                                                                                stroke="currentColor"
                                                                                stroke-width="2">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                            </svg>
                                                                            View Insights
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </main>

        {{-- ═══════════════════════════════════════
             INSIGHTS SLIDE-OVER
        ═══════════════════════════════════════ --}}
        {{-- Overlay --}}
        <div x-show="slideoverOpen" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40"
            @click="slideoverOpen = false" x-cloak></div>

        {{-- Panel --}}
        <div x-show="slideoverOpen" x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="fixed right-0 top-0 bottom-0 w-full md:w-[460px]
                    bg-white dark:bg-slate-900
                    shadow-2xl z-50 flex flex-col
                    border-l border-slate-200 dark:border-slate-700"
            x-cloak>

            {{-- Panel header --}}
            <div
                class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 shrink-0">
                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white text-base"
                        x-text="activeNode ? 'Section ' + activeNode.section_number + ' Insights' : ''"></h2>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Case laws &amp; global perspectives
                    </p>
                </div>
                <button @click="slideoverOpen = false"
                    class="w-8 h-8 flex items-center justify-center rounded-lg
                               text-slate-400 hover:text-slate-600 dark:hover:text-slate-200
                               bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700
                               transition-colors duration-150">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Panel body --}}
            <div class="flex-1 overflow-y-auto custom-scroll p-6 space-y-8">

                {{-- Case Laws --}}
                <template x-if="activeNode && activeNode.case_laws && activeNode.case_laws.length > 0">
                    <div>
                        <h3
                            class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-4 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                            Case Law Precedents
                        </h3>
                        <div class="space-y-4">
                            <template x-for="cl in activeNode.case_laws" :key="cl.id">
                                <div class="content-card overflow-hidden">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-brand-500"></div>
                                    <div class="content-card-body pl-4">
                                        <h4 class="font-semibold text-slate-900 dark:text-white text-sm"
                                            x-text="cl.case_title"></h4>
                                        <p class="text-xs text-brand-600 dark:text-brand-400 mt-0.5 mb-2 font-mono"
                                            x-text="cl.citation"></p>
                                        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed"
                                            x-text="cl.relevance_summary"></p>
                                        <template x-if="cl.url">
                                            <a :href="cl.url" target="_blank"
                                                class="inline-flex items-center gap-1 mt-3 text-xs font-semibold text-brand-600 dark:text-brand-400 hover:underline">
                                                Read Judgment
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- International Comparisons --}}
                <template
                    x-if="activeNode && activeNode.international_comparisons && activeNode.international_comparisons.length > 0">
                    <div>
                        <h3
                            class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-4 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Global Perspectives
                        </h3>
                        <div class="space-y-4">
                            <template x-for="comp in activeNode.international_comparisons" :key="comp.id">
                                <div class="content-card overflow-hidden">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500"></div>
                                    <div class="content-card-body pl-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span
                                                class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 text-xs font-bold rounded-full uppercase tracking-wide"
                                                x-text="comp.country"></span>
                                            <span class="text-xs text-slate-500 dark:text-slate-400"
                                                x-text="comp.constitution_provision"></span>
                                        </div>
                                        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed"
                                            x-text="comp.similarity_note"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Fallback --}}
                <template
                    x-if="activeNode && (!activeNode.case_laws || activeNode.case_laws.length === 0) && (!activeNode.international_comparisons || activeNode.international_comparisons.length === 0)">
                    <div class="empty-state py-20">
                        <div
                            class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <p class="font-semibold text-slate-700 dark:text-slate-300 text-sm">No insights yet</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Check back once contributors add
                            case laws and comparisons.</p>
                    </div>
                </template>

            </div>
        </div>

    </div>
</x-public-layout>
