<?php

namespace App\Http\Controllers;

use App\Models\ConstitutionNode;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $chapters = ConstitutionNode::where('type', 'chapter')
             ->where(function($q) {
                 $q->whereIn('status', ['published', 'ai_generated'])
                   ->orWhereHas('children', function($sq) {
                       $sq->whereIn('status', ['published', 'ai_generated'])
                          ->orWhereHas('children', function($ssq) {
                              $ssq->whereIn('status', ['published', 'ai_generated']);
                          });
                   });
             })
             ->with(['children' => function($q) {
                 $q->where(function($sq) {
                     $sq->whereIn('status', ['published', 'ai_generated'])
                        ->orWhereHas('children', function($ssq) {
                            $ssq->whereIn('status', ['published', 'ai_generated']);
                        });
                 })
                 ->with(['children' => function($sq) {
                     $sq->whereIn('status', ['published', 'ai_generated'])
                        ->with(['caseLaws', 'internationalComparisons', 'verifiedBy'])
                        ->orderBy('subsection_sort');
                 }])
                 ->with(['caseLaws', 'internationalComparisons', 'verifiedBy'])
                 ->orderBy('section_sort');
             }])
             ->orderBy('chapter_sort')
             ->get();

        return view('public.index', compact('chapters'));
    }

    public function about()
    {
        return view('public.about');
    }
}
