<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $offre->title }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('offres.edit', $offre) }}" class="inline-flex items-center px-4 py-2.5 bg-amber-500 text-white text-sm font-semibold rounded-xl hover:bg-amber-400 transition-all active:scale-[0.98]">
                    Modifier
                </a>
                <form action="{{ route('offres.destroy', $offre) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette offre ? Les analyses associées seront également supprimées.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-500 transition-all active:scale-[0.98]">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-200 shadow-card">
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Description</h3>
                        <p class="mt-2 text-gray-700 whitespace-pre-line">{{ $offre->description }}</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Compétences requises</h3>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach ($offre->required_skills as $skill)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                    {{ $skill }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Expérience minimale</h3>
                        <p class="mt-2 text-gray-700">{{ $offre->minimum_experience }} an(s)</p>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <a href="{{ route('offres.index') }}" class="text-indigo-600 hover:underline">&larr; Retour à la liste</a>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white rounded-2xl border border-gray-200 shadow-card">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Candidats soumis</h3>
                        <div class="flex gap-2">
                            <a href="{{ route('candidats.classement', $offre) }}" class="inline-flex items-center px-4 py-2.5 bg-white border border-brand-300 text-brand-700 text-sm font-semibold rounded-xl hover:bg-brand-50 transition-all active:scale-[0.98]">
                                Voir le classement
                            </a>
                            <a href="{{ route('candidats.create', $offre) }}" class="inline-flex items-center px-4 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-all active:scale-[0.98]">
                                + Soumettre un CV
                            </a>
                        </div>
                    </div>

                    @if ($offre->relationLoaded('candidats') && $offre->candidats->isNotEmpty())
                        <div class="divide-y divide-gray-200">
                            @foreach ($offre->candidats as $candidat)
                                <div class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $candidat->name }}</p>
                                        <p class="text-sm text-gray-500">Soumis le {{ $candidat->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @if ($candidat->analyse)
                                        @if ($candidat->analyse->status->value === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">En cours</span>
                                        @elseif ($candidat->analyse->status->value === 'completed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $candidat->analyse->matching_score >= 70 ? 'bg-green-100 text-green-800' : ($candidat->analyse->matching_score >= 40 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ $candidat->analyse->matching_score }}%
                                            </span>
                                        @elseif ($candidat->analyse->status->value === 'failed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Échec</span>
                                            @endif
                                        @endif
                                        <a href="{{ route('candidats.show', [$offre, $candidat]) }}" class="text-brand-600 hover:text-brand-700 text-sm font-medium">Voir l'analyse</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Aucun candidat soumis pour cette offre.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
