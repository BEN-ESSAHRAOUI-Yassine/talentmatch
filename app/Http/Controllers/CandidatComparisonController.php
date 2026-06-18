<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompareCandidatesRequest;
use App\Models\Offre;
use Illuminate\Support\Facades\Gate;

class CandidatComparisonController extends Controller
{
    public function classement(Offre $offre)
    {
        Gate::authorize('view', $offre);

        $analyses = $offre->analyses()
            ->with('candidat')
            ->get()
            ->sortByDesc(function ($analyse) {
                if ($analyse->status->value === 'completed') {
                    return 2 .sprintf('%04d', $analyse->matching_score);
                }
                if ($analyse->status->value === 'pending') {
                    return '1';
                }

                return '0';
            });

        return view('candidats.classement', [
            'offre' => $offre,
            'analyses' => $analyses,
        ]);
    }

    public function comparer(Offre $offre, CompareCandidatesRequest $request)
    {
        $ids = $request->input('ids');

        $analyses = $offre->analyses()
            ->whereIn('candidat_id', $ids)
            ->with('candidat')
            ->get();

        $analyse1 = $analyses->firstWhere('candidat_id', $ids[0]);
        $analyse2 = $analyses->firstWhere('candidat_id', $ids[1]);

        $bestScore = null;
        if ($analyse1 && $analyse2) {
            $bestScore = $analyse1->matching_score >= $analyse2->matching_score
                ? $analyse1->candidat_id
                : $analyse2->candidat_id;
        }

        return view('candidats.comparer', [
            'offre' => $offre,
            'candidat1' => $analyse1?->candidat,
            'analyse1' => $analyse1,
            'candidat2' => $analyse2?->candidat,
            'analyse2' => $analyse2,
            'bestScore' => $bestScore,
        ]);
    }
}
