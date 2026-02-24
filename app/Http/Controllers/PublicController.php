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
                 $q->where('status', 'published')
                   ->orWhereHas('children', function($sq) {
                       $sq->where('status', 'published')
                          ->orWhereHas('children', function($ssq) {
                              $ssq->where('status', 'published');
                          });
                   });
             })
             ->with(['children' => function($q) {
                 $q->where(function($sq) {
                     $sq->where('status', 'published')
                        ->orWhereHas('children', function($ssq) {
                            $ssq->where('status', 'published');
                        });
                 })
                 ->with(['children' => function($sq) {
                     $sq->where('status', 'published')
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
}
