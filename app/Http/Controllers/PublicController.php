<?php

namespace App\Http\Controllers;

use App\Models\ConstitutionNode;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $chapters = ConstitutionNode::where('type', 'chapter')
                        ->whereHas('children', function($q) {
                            $q->where('status', 'published');
                        })
                        ->with(['children' => function($q) {
                            $q->where('status', 'published')
                              ->with(['caseLaws', 'internationalComparisons', 'verifiedBy'])
                              ->orderBy('section_sort');
                        }])
                        ->orderBy('chapter_sort')
                        ->get();

        return view('public.index', compact('chapters'));
    }
}
