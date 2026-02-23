<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConstitutionNode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngestionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'hierarchy.chapter' => 'nullable|string',
            'hierarchy.chapter_title' => 'nullable|string',
            'hierarchy.section_number' => 'nullable|string',
            'hierarchy.section_title' => 'nullable|string',
            'hierarchy.subsection_number' => 'nullable|string',
            'content.legal_text' => 'required|string',
            'content.plain_english' => 'nullable|string',
            'content.keywords' => 'nullable|array',
            'cross_references.case_laws' => 'nullable|array',
            'cross_references.international_comparisons' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $parentId = null;
            if ($validated['type'] === 'section' && !empty($validated['hierarchy']['chapter'])) {
                $parent = ConstitutionNode::where('type', 'chapter')
                    ->where('chapter_number', $validated['hierarchy']['chapter'])
                    ->first();
                if ($parent) {
                    $parentId = $parent->id;
                }
            } elseif ($validated['type'] === 'subsection' && !empty($validated['hierarchy']['section_number'])) {
                $query = ConstitutionNode::where('type', 'section')
                    ->where('section_number', $validated['hierarchy']['section_number']);

                if (!empty($validated['hierarchy']['chapter'])) {
                    $query->where('chapter_number', $validated['hierarchy']['chapter']);
                }

                $parent = $query->first();
                if ($parent) {
                    $parentId = $parent->id;
                }
            }

            $node = ConstitutionNode::create([
                'type' => $validated['type'],
                'parent_id' => $parentId,
                'chapter_number' => $validated['hierarchy']['chapter'] ?? null,
                'chapter_title' => $validated['hierarchy']['chapter_title'] ?? null,
                'section_number' => $validated['hierarchy']['section_number'] ?? null,
                'section_title' => $validated['hierarchy']['section_title'] ?? null,
                'subsection_number' => $validated['hierarchy']['subsection_number'] ?? null,
                'legal_text' => $validated['content']['legal_text'],
                'plain_english' => $validated['content']['plain_english'] ?? null,
                'keywords' => $validated['content']['keywords'] ?? [],
                'status' => 'ai_generated',
            ]);

            if (!empty($validated['cross_references']['case_laws'])) {
                foreach ($validated['cross_references']['case_laws'] as $case) {
                    $node->caseLaws()->create($case);
                }
            }

            if (!empty($validated['cross_references']['international_comparisons'])) {
                foreach ($validated['cross_references']['international_comparisons'] as $comp) {
                    $node->internationalComparisons()->create($comp);
                }
            }

            DB::commit();

            return response()->json(['message' => 'Node successfully ingested.', 'node_id' => $node->id], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to ingest data.', 'error' => $e->getMessage()], 500);
        }
    }
}
