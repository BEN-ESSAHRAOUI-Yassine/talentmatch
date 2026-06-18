<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Comparaison — {{ $offre->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <nav class="mb-4 text-sm text-gray-500">
                <a href="{{ route('offres.index') }}" class="hover:text-indigo-600">Offres</a>
                <span class="mx-2">&rsaquo;</span>
                <a href="{{ route('offres.show', $offre) }}" class="hover:text-indigo-600">{{ $offre->title }}</a>
                <span class="mx-2">&rsaquo;</span>
                <a href="{{ route('candidats.classement', $offre) }}" class="hover:text-indigo-600">Classement</a>
                <span class="mx-2">&rsaquo;</span>
                <span class="text-gray-900">Comparaison</span>
            </nav>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @php $cards = [
                    ['candidat' => $candidat1, 'analyse' => $analyse1, 'side' => 'left'],
                    ['candidat' => $candidat2, 'analyse' => $analyse2, 'side' => 'right'],
                ]; @endphp

                @foreach ($cards as $card)
                    @php
                        $c = $card['candidat'];
                        $a = $card['analyse'];
                        $isBest = $bestScore === $c?->id;
                    @endphp
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg {{ $isBest ? 'ring-2 ring-indigo-500' : '' }}">
                        <div class="p-6">
                            @if ($isBest)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 mb-3">
                                    Meilleur score
                                </span>
                            @endif

                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $c?->name ?? 'Candidat introuvable' }}</h3>

                            @if ($a)
                                <div class="mb-4">
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-3xl font-bold {{ $a->matching_score >= 70 ? 'text-green-600' : ($a->matching_score >= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $a->matching_score }}%
                                        </span>
                                        <span class="text-sm text-gray-500">score de correspondance</span>
                                    </div>
                                    <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $a->matching_score >= 70 ? 'bg-green-500' : ($a->matching_score >= 40 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                             style="width: {{ $a->matching_score }}%"></div>
                                    </div>
                                </div>

                                @php $rec = $a->recommandation->value ?? $a->recommandation; @endphp
                                <div class="mb-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $rec === 'convoquer' ? 'bg-green-100 text-green-800' : ($rec === 'attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $rec === 'convoquer' ? 'Convoquer' : ($rec === 'attente' ? 'En attente' : 'Rejeter') }}
                                    </span>
                                </div>

                                <dl class="space-y-4 text-sm">
                                    <div>
                                        <dt class="font-medium text-gray-900">Compétences extraites</dt>
                                        <dd class="mt-1 text-gray-600">
                                            @if ($a->competences_extraites)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach ($a->competences_extraites as $skill)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700">{{ $skill }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-gray-400">Aucune</span>
                                            @endif
                                        </dd>
                                    </div>

                                    <div>
                                        <dt class="font-medium text-gray-900">Expérience</dt>
                                        <dd class="mt-1 text-gray-600">{{ $a->annees_experience }} an(s)</dd>
                                    </div>

                                    <div>
                                        <dt class="font-medium text-gray-900">Niveau d'études</dt>
                                        <dd class="mt-1 text-gray-600">{{ $a->niveau_etudes }}</dd>
                                    </div>

                                    <div>
                                        <dt class="font-medium text-gray-900">Langues</dt>
                                        <dd class="mt-1 text-gray-600">{{ $a->langues ? implode(', ', $a->langues) : 'Aucune' }}</dd>
                                    </div>

                                    <div>
                                        <dt class="font-medium text-gray-900">Points forts</dt>
                                        <dd class="mt-1">
                                            @if ($a->points_forts)
                                                <ul class="list-disc list-inside text-gray-600 space-y-1">
                                                    @foreach ($a->points_forts as $pf)
                                                        <li>{{ $pf }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-gray-400">Aucun</span>
                                            @endif
                                        </dd>
                                    </div>

                                    <div>
                                        <dt class="font-medium text-gray-900">Lacunes</dt>
                                        <dd class="mt-1">
                                            @if ($a->lacunes)
                                                <ul class="list-disc list-inside text-gray-600 space-y-1">
                                                    @foreach ($a->lacunes as $l)
                                                        <li>{{ $l }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-gray-400">Aucune</span>
                                            @endif
                                        </dd>
                                    </div>

                                    <div>
                                        <dt class="font-medium text-gray-900">Compétences manquantes</dt>
                                        <dd class="mt-1">
                                            @if ($a->competences_manquantes)
                                                <ul class="list-disc list-inside text-gray-600 space-y-1">
                                                    @foreach ($a->competences_manquantes as $cm)
                                                        <li>{{ $cm }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-gray-400">Aucune</span>
                                            @endif
                                        </dd>
                                    </div>

                                    <div>
                                        <dt class="font-medium text-gray-900">Justification</dt>
                                        <dd class="mt-1 text-gray-600">{{ $a->justification }}</dd>
                                    </div>
                                </dl>
                            @else
                                <p class="text-gray-400 text-sm">Analyse non disponible</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('candidats.classement', $offre) }}" class="text-indigo-600 hover:underline">&larr; Retour au classement</a>
            </div>
        </div>
    </div>
</x-app-layout>
