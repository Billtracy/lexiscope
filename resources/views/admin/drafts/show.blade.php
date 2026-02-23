<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.drafts.index') }}"
                class="text-brand-600 dark:text-brand-400 hover:underline font-medium text-sm">Drafts</a>
            <svg class="w-4 h-4 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-sm text-slate-500 dark:text-slate-400">
                {{ ucfirst($draft->type) }} {{ $draft->section_number }}
            </span>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-64 sm:pb-32" x-data="reviewForm({{ Js::from($draft->caseLaws) }}, {{ Js::from($draft->internationalComparisons) }})">

        @if ($errors->any())
            <div class="alert-error mb-5">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Lock Banner --}}
        @if ($draft->locked_by && $draft->locked_by !== auth()->id() && auth()->user()->role !== 'admin')
            <div
                class="flex items-start gap-3 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800
                        dark:bg-amber-900/30 dark:border-amber-700 dark:text-amber-300 mb-5 text-sm">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="font-semibold">Currently locked</p>
                    <p class="mt-0.5 opacity-80">{{ $draft->lockedBy->name ?? 'Another contributor' }} is currently
                        editing this section. You may view but cannot save changes.</p>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.drafts.update', $draft) }}" id="review-form">
            @csrf
            @method('PUT')

            <div class="space-y-5">

                {{-- ── Legal Text & Plain English ─── --}}
                <div class="content-card">
                    <div class="content-card-header">
                        <h2 class="font-semibold text-slate-800 dark:text-white text-sm">Core Content</h2>
                        <span
                            class="badge-{{ $draft->status === 'ai_generated' ? 'ai' : ($draft->status === 'draft' ? 'draft' : 'published') }}">
                            {{ ucfirst(str_replace('_', ' ', $draft->status)) }}
                        </span>
                    </div>
                    <div class="content-card-body">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                            <div>
                                <label for="legal_text" class="field-label">Constitutional Legal Text</label>
                                <textarea id="legal_text" name="legal_text" rows="12" class="field-textarea mt-1" required>{{ old('legal_text', $draft->legal_text) }}</textarea>
                            </div>
                            <div>
                                <label for="plain_english" class="field-label">
                                    Plain English Translation
                                    <span class="ml-1 font-normal text-slate-400 text-xs">(AI — review &amp;
                                        edit)</span>
                                </label>
                                <textarea id="plain_english" name="plain_english" rows="12" class="field-textarea mt-1" required>{{ old('plain_english', $draft->plain_english) }}</textarea>
                            </div>
                        </div>
                        <div class="mt-5">
                            <label for="keywords" class="field-label">Keywords <span
                                    class="font-normal text-slate-400 text-xs">(comma-separated)</span></label>
                            <input id="keywords" name="keywords" type="text" class="field-input mt-1"
                                value="{{ old('keywords', implode(', ', $draft->keywords ?? [])) }}"
                                placeholder="e.g. fundamental rights, freedom of speech, …">
                        </div>
                    </div>
                </div>

                {{-- ── Case Laws ─────────────────── --}}
                <div class="content-card">
                    <div class="content-card-header">
                        <h2 class="font-semibold text-slate-800 dark:text-white text-sm">Case Law References</h2>
                        <button type="button" @click="addCaseLaw()" class="btn-primary btn-sm">
                            + Add
                        </button>
                    </div>
                    <div class="content-card-body space-y-4">

                        <template x-for="(cl, index) in caseLaws" :key="index">
                            <div
                                class="relative bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700 p-4">
                                <button type="button" @click="removeCaseLaw(index)"
                                    class="absolute top-3 right-3 w-6 h-6 rounded-full flex items-center justify-center
                                               text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <input type="hidden" :name="`case_laws[${index}][id]`" :value="cl.id">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-3 pr-8">
                                    <div>
                                        <label class="field-label">Case Title</label>
                                        <input type="text" :name="`case_laws[${index}][case_title]`"
                                            x-model="cl.case_title" class="field-input mt-1" required>
                                    </div>
                                    <div>
                                        <label class="field-label">Citation</label>
                                        <input type="text" :name="`case_laws[${index}][citation]`"
                                            x-model="cl.citation" class="field-input mt-1" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="field-label">Relevance Summary</label>
                                    <textarea :name="`case_laws[${index}][relevance_summary]`" x-model="cl.relevance_summary" rows="2"
                                        class="field-input mt-1 resize-y"></textarea>
                                </div>
                                <div>
                                    <label class="field-label">URL</label>
                                    <input type="url" :name="`case_laws[${index}][url]`" x-model="cl.url"
                                        class="field-input mt-1" placeholder="https://…">
                                </div>
                            </div>
                        </template>

                        <div x-show="caseLaws.length === 0"
                            class="text-center py-8 text-sm text-slate-400 dark:text-slate-500">
                            No case laws added yet. Click <strong>+ Add</strong> to reference one.
                        </div>

                    </div>
                </div>

                {{-- ── International Comparisons ─── --}}
                <div class="content-card">
                    <div class="content-card-header">
                        <h2 class="font-semibold text-slate-800 dark:text-white text-sm">International Comparisons</h2>
                        <button type="button" @click="addComparison()" class="btn-primary btn-sm">
                            + Add
                        </button>
                    </div>
                    <div class="content-card-body space-y-4">

                        <template x-for="(comp, index) in comparisons" :key="index">
                            <div
                                class="relative bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700 p-4">
                                <button type="button" @click="removeComparison(index)"
                                    class="absolute top-3 right-3 w-6 h-6 rounded-full flex items-center justify-center
                                               text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <input type="hidden" :name="`comparisons[${index}][id]`" :value="comp.id">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-3 pr-8">
                                    <div>
                                        <label class="field-label">Country</label>
                                        <input type="text" :name="`comparisons[${index}][country]`"
                                            x-model="comp.country" class="field-input mt-1" required>
                                    </div>
                                    <div>
                                        <label class="field-label">Provision / Article</label>
                                        <input type="text" :name="`comparisons[${index}][constitution_provision]`"
                                            x-model="comp.constitution_provision" class="field-input mt-1">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="field-label">Similarity Note</label>
                                    <textarea :name="`comparisons[${index}][similarity_note]`" x-model="comp.similarity_note" rows="2"
                                        class="field-input mt-1 resize-y"></textarea>
                                </div>
                                <div>
                                    <label class="field-label">URL</label>
                                    <input type="url" :name="`comparisons[${index}][related_link]`"
                                        x-model="comp.related_link" class="field-input mt-1" placeholder="https://…">
                                </div>
                            </div>
                        </template>

                        <div x-show="comparisons.length === 0"
                            class="text-center py-8 text-sm text-slate-400 dark:text-slate-500">
                            No international comparisons added yet. Click <strong>+ Add</strong> to add one.
                        </div>

                    </div>
                </div>

            </div>{{-- end .space-y-5 --}}

        </form>
    </div>

    {{-- ── Sticky Action Bar ──────────────────────────────── --}}
    {{-- fixed bottom-0 always visible; pb-20 on mobile pushes buttons above the bottom nav --}}
    <div
        class="fixed bottom-0 inset-x-0 z-30
                bg-white/95 dark:bg-slate-800/95 backdrop-blur-md
                border-t border-slate-200 dark:border-slate-700
                px-4 pt-3 pb-20 sm:py-3 sm:px-6">
        {{-- Mobile: full-width stacked buttons; sm+: horizontal row --}}
        <div class="max-w-7xl mx-auto flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between sm:gap-3">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                <button type="submit" form="review-form" name="action" value="save"
                    class="btn-ghost border border-slate-200 dark:border-slate-600
                               py-2.5 px-4 text-sm font-semibold text-center w-full sm:w-auto">
                    Save Draft
                </button>
                <button type="submit" form="review-form" name="action" value="publish"
                    class="btn-success py-2.5 px-5 text-sm text-center w-full sm:w-auto">
                    ✓ Verify &amp; Publish
                </button>
            </div>
            <a href="{{ route('admin.drafts.index') }}"
                class="btn-ghost text-sm py-2.5 text-center w-full sm:text-left sm:w-auto">
                ← Cancel
            </a>
        </div>
    </div>


    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('reviewForm', (initialCaseLaws, initialComparisons) => ({
                    caseLaws: initialCaseLaws || [],
                    comparisons: initialComparisons || [],

                    addCaseLaw() {
                        this.caseLaws.push({
                            id: null,
                            case_title: '',
                            citation: '',
                            relevance_summary: '',
                            url: ''
                        });
                    },
                    removeCaseLaw(index) {
                        this.caseLaws.splice(index, 1);
                    },

                    addComparison() {
                        this.comparisons.push({
                            id: null,
                            country: '',
                            constitution_provision: '',
                            similarity_note: '',
                            related_link: ''
                        });
                    },
                    removeComparison(index) {
                        this.comparisons.splice(index, 1);
                    }
                }));
            });
        </script>
    @endpush

</x-app-layout>
