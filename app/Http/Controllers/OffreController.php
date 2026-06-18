<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOffreRequest;
use App\Http\Requests\UpdateOffreRequest;
use App\Models\Offre;
use Illuminate\Http\Request;

class OffreController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $offres = Offre::where('user_id', auth()->id())
            ->when($search, fn ($query, $search) => $query->where('title', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10);

        return view('offres.index', compact('offres', 'search'));
    }

    public function create()
    {
        return view('offres.create');
    }

    public function store(StoreOffreRequest $request)
    {
        $offre = auth()->user()->offres()->create($request->validated());

        return redirect()->route('offres.show', $offre)
            ->with('success', 'Offre créée avec succès.');
    }

    public function show(Offre $offre)
    {
        $this->authorize('view', $offre);

        $offre->load('candidats.analyse');

        return view('offres.show', compact('offre'));
    }

    public function edit(Offre $offre)
    {
        $this->authorize('update', $offre);

        return view('offres.edit', compact('offre'));
    }

    public function update(UpdateOffreRequest $request, Offre $offre)
    {
        $this->authorize('update', $offre);

        $offre->update($request->validated());

        return redirect()->route('offres.show', $offre)
            ->with('success', 'Offre mise à jour avec succès.');
    }

    public function destroy(Offre $offre)
    {
        $this->authorize('delete', $offre);

        $offre->delete();

        return redirect()->route('offres.index')
            ->with('success', 'Offre supprimée avec succès.');
    }
}
