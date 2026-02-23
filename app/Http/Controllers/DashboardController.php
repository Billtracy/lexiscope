<?php

namespace App\Http\Controllers;

use App\Models\ConstitutionNode;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Per-user stats
        $stats = [
            'published'    => ConstitutionNode::where('verified_by', $user->id)->where('status', 'published')->count(),
            'in_progress'  => ConstitutionNode::where('locked_by',   $user->id)->where('status', 'draft')->count(),
            'ai_generated' => ConstitutionNode::where('status', 'ai_generated')->count(),
        ];

        // Top contributors leaderboard (top 10 by published nodes)
        $leaderboard = User::select('users.id', 'users.name', 'users.role')
            ->selectRaw('COUNT(constitution_nodes.id) as published_count')
            ->join('constitution_nodes', function ($join) {
                $join->on('users.id', '=', 'constitution_nodes.verified_by')
                     ->where('constitution_nodes.status', '=', 'published');
            })
            ->groupBy('users.id', 'users.name', 'users.role')
            ->orderByDesc('published_count')
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'leaderboard'));
    }
}
