<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidatRequest;
use App\Jobs\AnalyseCvJob;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;

class CandidatController extends Controller
{
    public function create(Offre $offre)
    {
        $this->authorize('create', [Candidat::class, $offre]);

        return view('candidats.create', compact('offre'));
    }

    public function store(StoreCandidatRequest $request, Offre $offre)
    {
        $this->authorize('create', [Candidat::class, $offre]);

        $candidat = Candidat::create($request->validated());

        $analyse = Analyse::create([
            'offre_id' => $offre->id,
            'candidat_id' => $candidat->id,
            'status' => 'pending',
        ]);

        AnalyseCvJob::dispatch($offre->id, $candidat->id, $analyse->id);

        return redirect()->route('candidats.show', [$offre, $candidat])
            ->with('success', 'CV soumis avec succès. L\'analyse est en cours.');
    }

    public function show(Offre $offre, Candidat $candidat)
    {
        $this->authorize('view', $candidat);

        $candidat->load('analyse.agentConversation');

        return view('candidats.show', compact('offre', 'candidat'));
    }
}
