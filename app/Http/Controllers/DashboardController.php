<?php

namespace App\Http\Controllers;

use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $userId = auth()->id();

        $offresCount = Offre::where('user_id', $userId)->count();
        $candidatsCount = Candidat::query()
            ->whereHas('analyse.offre', fn ($q) => $q->where('user_id', $userId))
            ->count();

        $pendingAnalyses = Analyse::whereHas('offre', fn ($q) => $q->where('user_id', $userId))
            ->where('status', 'pending')
            ->count();

        $completedAnalyses = Analyse::whereHas('offre', fn ($q) => $q->where('user_id', $userId))
            ->where('status', 'completed')
            ->count();

        $recentSubmissions = Candidat::whereHas('analyse.offre', fn ($q) => $q->where('user_id', $userId))
            ->with('analyse')
            ->latest()
            ->limit(5)
            ->get();

        $hasOffres = $offresCount > 0;
        $hasCandidats = $candidatsCount > 0;

        return view('dashboard', compact(
            'offresCount',
            'candidatsCount',
            'pendingAnalyses',
            'completedAnalyses',
            'recentSubmissions',
            'hasOffres',
            'hasCandidats',
        ));
    }
}
