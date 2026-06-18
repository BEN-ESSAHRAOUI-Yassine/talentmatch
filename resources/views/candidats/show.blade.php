<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Analyse — {{ $candidat->name }}
            </h2>
            <a href="{{ route('offres.show', $offre) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                &larr; Retour à l'offre
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @php $analyse = $candidat->analyse; @endphp

                    @if ($analyse->status->value === 'pending')
                        <div class="text-center py-12">
                            <svg class="animate-spin h-12 w-12 text-indigo-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Analyse en cours</h3>
                            <p class="mt-2 text-gray-500">L'analyse du CV est en cours de traitement. Rechargez la page pour voir les résultats.</p>
                        </div>

                    @elseif ($analyse->status->value === 'failed')
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mb-4">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Analyse échouée</h3>
                            @if ($analyse->error_message)
                                <p class="mt-2 text-red-600">{{ $analyse->error_message }}</p>
                            @else
                                <p class="mt-2 text-gray-500">Une erreur est survenue lors de l'analyse.</p>
                            @endif
                        </div>

                    @elseif ($analyse->status->value === 'completed')
                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Détails du candidat</h3>
                                    <p class="mt-1 text-gray-600">{{ $candidat->name }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-4xl font-bold {{ $analyse->matching_score >= 70 ? 'text-green-600' : ($analyse->matching_score >= 40 ? 'text-amber-500' : 'text-red-600') }}">
                                        {{ $analyse->matching_score }}%
                                    </div>
                                    <div class="text-sm text-gray-500">Score de matching</div>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold
                                    {{ $analyse->recommandation === 'convoquer' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $analyse->recommandation === 'attente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $analyse->recommandation === 'rejeter' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $analyse->recommandation->value === 'convoquer' ? 'À convoquer' : ($analyse->recommandation->value === 'attente' ? 'En attente' : 'À rejeter') }}
                                </span>
                            </div>

                            @if ($analyse->competences_extraites)
                            <div>
                                <h4 class="font-medium text-gray-900">Compétences extraites</h4>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach ($analyse->competences_extraites as $skill)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if ($analyse->annees_experience)
                                <div>
                                    <h4 class="font-medium text-gray-900">Années d'expérience</h4>
                                    <p class="mt-1 text-gray-700">{{ $analyse->annees_experience }} an(s)</p>
                                </div>
                                @endif

                                @if ($analyse->niveau_etudes)
                                <div>
                                    <h4 class="font-medium text-gray-900">Niveau d'études</h4>
                                    <p class="mt-1 text-gray-700">{{ $analyse->niveau_etudes }}</p>
                                </div>
                                @endif
                            </div>

                            @if ($analyse->langues)
                            <div>
                                <h4 class="font-medium text-gray-900">Langues</h4>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach ($analyse->langues as $langue)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">{{ $langue }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if ($analyse->points_forts)
                            <div>
                                <h4 class="font-medium text-green-700">Points forts</h4>
                                <ul class="mt-2 list-disc list-inside text-gray-700">
                                    @foreach ($analyse->points_forts as $point)
                                        <li>{{ $point }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @if ($analyse->lacunes)
                            <div>
                                <h4 class="font-medium text-amber-700">Lacunes</h4>
                                <ul class="mt-2 list-disc list-inside text-gray-700">
                                    @foreach ($analyse->lacunes as $lacune)
                                        <li>{{ $lacune }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @if ($analyse->competences_manquantes)
                            <div>
                                <h4 class="font-medium text-red-700">Compétences manquantes</h4>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach ($analyse->competences_manquantes as $skill)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if ($analyse->justification)
                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="font-medium text-gray-900">Justification</h4>
                                <p class="mt-2 text-gray-700 whitespace-pre-line">{{ $analyse->justification }}</p>
                            </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
