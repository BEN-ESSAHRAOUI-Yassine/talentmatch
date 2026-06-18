<?php

namespace App\Http\Controllers;

use App\Jobs\AnalyseCvJob;
use App\Models\Analyse;

class AnalyseController extends Controller
{
    public function retry(Analyse $analyse)
    {
        $this->authorize('retry', $analyse);

        $analyse->update([
            'status' => 'pending',
            'error_message' => null,
        ]);

        AnalyseCvJob::dispatch($analyse->offre_id, $analyse->candidat_id, $analyse->id);

        return redirect()->back()->with('success', 'Analyse relancée avec succès.');
    }
}
