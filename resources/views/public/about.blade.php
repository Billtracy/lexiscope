<x-public-layout>
    <div class="flex flex-col h-full w-full bg-slate-50 dark:bg-slate-950 overflow-hidden">
        {{-- Header --}}
        <header class="w-full h-16 flex items-center justify-between px-6 md:px-12 border-b border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md shrink-0">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                <img src="{{ asset('logo.png') }}" class="w-8 h-8 rounded-lg shadow-sm object-cover group-hover:opacity-90 transition" alt="Lexiscope Logo" />
                <span class="font-bold text-lg text-slate-900 dark:text-white">Lexiscope</span>
            </a>
            
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 transition">
                    Back to Constitution
                </a>
            </div>
        </header>

        {{-- Scrollable Main Content --}}
        <div class="flex-1 overflow-y-auto custom-scroll w-full">
            <main class="w-full max-w-4xl mx-auto px-6 py-16 md:py-24">
                
                <div class="text-center mb-16">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brand-50 dark:bg-brand-900/30 border border-brand-200 dark:border-brand-700 text-brand-700 dark:text-brand-400 text-xs font-semibold uppercase tracking-wider mb-5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        About The Project
                    </div>
                    <h1 class="font-serif text-4xl md:text-5xl font-bold mb-6 text-slate-900 dark:text-white leading-tight">
                        Democratizing Legal <br class="hidden md:block"> Knowledge
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
                        Lexiscope is an interactive legal research tool designed to make the Constitution of the Federal Republic of Nigeria (1999) accessible to everyone.
                    </p>
                </div>

                <div class="space-y-16">
                    {{-- Section 1 --}}
                    <div class="grid md:grid-cols-2 gap-10 md:gap-16 items-center">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-brand-100 dark:bg-brand-900/30 flex items-center justify-center mb-6">
                                <svg class="w-6 h-6 text-brand-600 dark:text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <h2 class="font-serif text-2xl md:text-3xl font-bold text-slate-900 dark:text-white mb-4">
                                Powered by AI
                            </h2>
                            <div class="prose prose-slate dark:prose-invert font-sans text-slate-600 dark:text-slate-300">
                                <p>
                                    Understanding legal terminology can be daunting. Lexiscope uses advanced Artificial Intelligence to parse complex constitutional texts and generate <strong>plain English explanations</strong>. 
                                </p>
                                <p>
                                    The AI model also extracts key themes and cross-references relevant case laws and international precedents, enriching the constitutional reading experience with global context.
                                </p>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl p-8 border border-slate-100 dark:border-slate-800">
                            <div class="flex items-start gap-4 mb-4">
                                <span class="px-2 py-1 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-xs font-bold rounded uppercase tracking-wider flex items-center gap-1.5 border border-amber-100 dark:border-amber-800">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Unverified AI Result
                                </span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                While our AI engine processes legal texts rapidly, it may occasionally lack the nuanced understanding of a trained legal professional. Because of this, AI-generated results are initially labeled as <strong>Unverified</strong> to maintain transparency with our readers.
                            </p>
                        </div>
                    </div>

                    {{-- Section 2 --}}
                    <div class="grid md:grid-cols-2 gap-10 md:gap-16 items-center flex-col-reverse md:flex-row-reverse">
                        <div class="order-1 md:order-2">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-6">
                                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="font-serif text-2xl md:text-3xl font-bold text-slate-900 dark:text-white mb-4">
                                Verified by Experts
                            </h2>
                            <div class="prose prose-slate dark:prose-invert font-sans text-slate-600 dark:text-slate-300">
                                <p>
                                    Technology is best used alongside human expertise. To ensure absolute accuracy, Lexiscope incorporates a <strong>human-in-the-loop verification layer</strong>.
                                </p>
                                <p>
                                    Legal professionals and academic contributors review the AI-generated summaries, case laws, and global perspectives. Once reviewed and approved, the section receives a "Verified by" badge, providing readers with the confidence that the information is legally sound and accurate.
                                </p>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl p-8 border border-slate-100 dark:border-slate-800 order-2 md:order-1">
                            <div class="flex items-start gap-4 mb-4">
                                <span class="px-2 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold rounded uppercase tracking-wider flex items-center gap-1.5 border border-emerald-100 dark:border-emerald-800">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Verified by Expert
                                </span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                Contributors can log in to our dashboard to review drafts, modify plain English explanations, and formally verify content. This collaborative approach builds a highly reliable and continually improving legal research platform.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-24 text-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 dark:bg-brand-600 dark:hover:bg-brand-500 text-white font-semibold py-3 px-8 rounded-xl transition duration-200">
                        Explore the Constitution
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

            </main>
            
            {{-- Footer --}}
            <footer class="w-full py-8 mt-12 border-t border-slate-200 dark:border-slate-800 text-center text-slate-500 dark:text-slate-400 text-sm shrink-0">
                <p>&copy; {{ date('Y') }} Lexiscope. All rights reserved.</p>
            </footer>
        </div>
    </div>
</x-public-layout>
