<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConstitutionNode;
use Illuminate\Http\Request;

class DraftReviewController extends Controller
{
    public function index()
    {
        // Show all sections that have children (subsections) needing review
        $sections = ConstitutionNode::where('type', 'section')
            ->whereHas('children', function ($query) {
                $query->whereIn('status', ['ai_generated', 'draft']);
            })
            ->orderBy('chapter_sort')
            ->orderBy('section_sort')
            ->get();

        return view('admin.drafts.index', compact('sections'));
    }

    public function section(ConstitutionNode $section)
    {
        // Show subsections of this section
        $subsections = $section->children()->orderBy('subsection_sort')->with('lockedBy')->get();
        return view('admin.drafts.section', compact('section', 'subsections'));
    }

    public function create()
    {
        return view('admin.drafts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:chapter,section,subsection',
            'parent_id' => 'nullable|exists:constitution_nodes,id',
            'chapter' => 'nullable|string',
            'chapter_title' => 'nullable|string',
            'section_number' => 'nullable|string',
            'section_title' => 'nullable|string',
            'subsection_number' => 'nullable|string',
            'legal_text' => 'required|string',
            'plain_english' => 'nullable|string',
            'keywords' => 'nullable|string',
            'status' => 'required|in:draft,published',
        ]);

        $keywords = collect(explode(',', $validated['keywords'] ?? ''))->map(fn($val) => trim($val))->filter()->values()->toArray();

        // Basic sorting logic (usually handled better in real world, but for manual entry we do our best)
        $chapterSort = intval(preg_replace('/[^0-9]/', '', $validated['chapter'] ?? '0'));
        $sectionSort = intval(preg_replace('/[^0-9]/', '', $validated['section_number'] ?? '0'));
        $subsectionSort = intval(preg_replace('/[^0-9]/', '', $validated['subsection_number'] ?? '0'));

        $node = ConstitutionNode::create([
            'type' => $validated['type'],
            'parent_id' => $validated['parent_id'],
            'chapter' => $validated['chapter'],
            'chapter_title' => $validated['chapter_title'],
            'section_number' => $validated['section_number'],
            'section_title' => $validated['section_title'],
            'subsection_number' => $validated['subsection_number'],
            'legal_text' => $validated['legal_text'],
            'plain_english' => $validated['plain_english'],
            'keywords' => $keywords,
            'status' => $validated['status'],
            'chapter_sort' => $chapterSort,
            'section_sort' => $sectionSort,
            'subsection_sort' => $subsectionSort,
            'locked_by' => null,
            'verified_by' => $validated['status'] === 'published' ? auth()->id() : null,
            'verified_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        return redirect()->route('admin.drafts.index')->with('status', 'Node created successfully.');
    }

    public function show(ConstitutionNode $draft)
    {
        // Permission Check
        if ($draft->status === 'draft' && $draft->locked_by !== auth()->id() && auth()->user()->role !== 'admin') {
            return redirect()->route('admin.drafts.index')->withErrors('This section is currently being edited by someone else.');
        }

        $draft->load(['caseLaws', 'internationalComparisons']);
        return view('admin.drafts.show', compact('draft'));
    }

    public function update(Request $request, ConstitutionNode $draft)
    {
        if ($draft->status === 'draft' && $draft->locked_by !== auth()->id() && auth()->user()->role !== 'admin') {
            return redirect()->route('admin.drafts.index')->withErrors('You do not have permission to edit this draft.');
        }

        $validated = $request->validate([
            'legal_text' => 'required|string',
            'plain_english' => 'required|string',
            'keywords' => 'nullable|string',
            'action' => 'required|in:save,publish',

            // Case laws
            'case_laws' => 'nullable|array',
            'case_laws.*.id' => 'nullable|integer',
            'case_laws.*.case_title' => 'required_with:case_laws|string',
            'case_laws.*.citation' => 'required_with:case_laws|string',
            'case_laws.*.relevance_summary' => 'nullable|string',
            'case_laws.*.url' => 'nullable|url',

            // Comparisons
            'comparisons' => 'nullable|array',
            'comparisons.*.id' => 'nullable|integer',
            'comparisons.*.country' => 'required_with:comparisons|string',
            'comparisons.*.constitution_provision' => 'nullable|string',
            'comparisons.*.similarity_note' => 'nullable|string',
            'comparisons.*.related_link' => 'nullable|url',
        ]);

        $keywords = collect(explode(',', $validated['keywords'] ?? ''))->map(fn($val) => trim($val))->filter()->values()->toArray();

        // Update main node
        $draft->update([
            'legal_text' => $validated['legal_text'],
            'plain_english' => $validated['plain_english'],
            'keywords' => $keywords,
            'status' => $validated['action'] === 'publish' ? 'published' : 'draft',
            'locked_by' => $validated['action'] === 'publish' ? null : auth()->id(),
            'verified_by' => $validated['action'] === 'publish' ? auth()->id() : $draft->verified_by,
            'verified_at' => $validated['action'] === 'publish' ? now() : $draft->verified_at,
        ]);

        // Sync Case Laws
        $existingCaseLawIds = collect($validated['case_laws'] ?? [])->pluck('id')->filter()->toArray();
        $draft->caseLaws()->whereNotIn('id', $existingCaseLawIds)->delete();

        foreach ($validated['case_laws'] ?? [] as $caseData) {
            if (!empty($caseData['id'])) {
                $draft->caseLaws()->where('id', $caseData['id'])->update(\Illuminate\Support\Arr::except($caseData, ['id']));
            } else {
                $draft->caseLaws()->create(\Illuminate\Support\Arr::except($caseData, ['id']));
            }
        }

        // Sync Comparisons
        $existingCompIds = collect($validated['comparisons'] ?? [])->pluck('id')->filter()->toArray();
        $draft->internationalComparisons()->whereNotIn('id', $existingCompIds)->delete();

        foreach ($validated['comparisons'] ?? [] as $compData) {
            if (!empty($compData['id'])) {
                $draft->internationalComparisons()->where('id', $compData['id'])->update(\Illuminate\Support\Arr::except($compData, ['id']));
            } else {
                $draft->internationalComparisons()->create(\Illuminate\Support\Arr::except($compData, ['id']));
            }
        }

        $msg = $validated['action'] === 'publish' ? 'Section published successfully!' : 'Draft saved and locked for your review.';
        return redirect()->route('admin.drafts.index')->with('status', $msg);
    }
}
