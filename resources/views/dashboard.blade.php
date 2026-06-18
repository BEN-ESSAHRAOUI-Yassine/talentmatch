<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Bon retour, {{ auth()->user()->name }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    @if ($hasOffres && $hasCandidats)
                        {{ $offresCount }} offre(s) · {{ $candidatsCount }} candidat(s) soumis
                    @elseif ($hasOffres)
                        {{ $offresCount }} offre(s) — soumettez un CV pour démarrer l'analyse
                    @else
                        Commencez par créer votre première offre d'emploi
                    @endif
                </p>
            </div>
            @if ($hasOffres)
                <a href="{{ route('offres.create') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 transition-all active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nouvelle offre
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if (session('success'))
                <div class="px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (!$hasOffres)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-card p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-brand-100 mb-6">
                        <svg class="w-8 h-8 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Bienvenue sur TalentMatch</h3>
                    <p class="text-gray-500 max-w-md mx-auto mb-8">
                        Centralisez vos offres d'emploi, analysez les CV par IA et comparez les candidats en quelques clics.
                    </p>
                    <a href="{{ route('offres.create') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 transition-all active:scale-[0.98]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Créer ma première offre
                    </a>
                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-lg mx-auto text-left">
                        <div class="flex items-start gap-3">
                            <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-brand-100 text-brand-700 text-xs font-bold shrink-0">1</span>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Créez une offre</p>
                                <p class="text-xs text-gray-500">Décrivez le poste et les compétences requises</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-brand-100 text-brand-700 text-xs font-bold shrink-0">2</span>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Soumettez des CV</p>
                                <p class="text-xs text-gray-500">L'IA analyse et score chaque candidat</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-brand-100 text-brand-700 text-xs font-bold shrink-0">3</span>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Comparez & décidez</p>
                                <p class="text-xs text-gray-500">Classez, comparez et convoquez les meilleurs</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-card p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-brand-100">
                                <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-brand-50 text-brand-700">Actives</span>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 tracking-tight">{{ $offresCount }}</div>
                        <div class="text-sm text-gray-500 mt-0.5">Offre(s) d'emploi</div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-200 shadow-card p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-blue-100">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 tracking-tight">{{ $candidatsCount }}</div>
                        <div class="text-sm text-gray-500 mt-0.5">Candidat(s) soumis</div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-200 shadow-card p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-amber-100">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                            </div>
                            @if ($pendingAnalyses > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 animate-pulse">{{ $pendingAnalyses }} en attente</span>
                            @endif
                        </div>
                        <div class="text-3xl font-bold text-gray-900 tracking-tight">{{ $pendingAnalyses }}</div>
                        <div class="text-sm text-gray-500 mt-0.5">Analyse(s) en cours</div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-200 shadow-card p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-100">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 tracking-tight">{{ $completedAnalyses }}</div>
                        <div class="text-sm text-gray-500 mt-0.5">Analyse(s) terminées</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-card">
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">Soumissions récentes</h3>
                            @if ($candidatsCount > 0)
                                <a href="{{ route('candidats.index') }}" class="text-sm text-brand-600 hover:text-brand-700 font-medium">Voir tout</a>
                            @endif
                        </div>
                        <div class="p-5">
                            @if ($recentSubmissions->isNotEmpty())
                                <div class="space-y-3">
                                    @foreach ($recentSubmissions as $candidat)
                                        @php $analyse = $candidat->analyse; @endphp
                                        <div class="flex items-center justify-between py-2">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gray-100 text-gray-600 text-sm font-bold shrink-0">
                                                    {{ strtoupper(substr($candidat->name, 0, 2)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $candidat->name }}</p>
                                                    <p class="text-xs text-gray-500">Soumis le {{ $candidat->created_at->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3 shrink-0">
                                                @if ($analyse)
                                                    @if ($analyse->status->value === 'pending')
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                            En cours
                                                        </span>
                                                    @elseif ($analyse->status->value === 'completed')
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold
                                                            {{ $analyse->matching_score >= 70 ? 'bg-emerald-50 text-emerald-700' : ($analyse->matching_score >= 40 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                                                            {{ $analyse->matching_score }}%
                                                        </span>
                                                        <a href="{{ route('candidats.show', [$analyse->offre_id, $candidat]) }}" class="text-sm text-brand-600 hover:text-brand-700 font-medium">Voir</a>
                                                    @elseif ($analyse->status->value === 'failed')
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700">Échec</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm text-center py-6">Aucun candidat soumis pour le moment.</p>
                                <div class="text-center">
                                    <a href="{{ route('offres.index') }}" class="text-sm text-brand-600 hover:text-brand-700 font-medium">Choisir une offre &rarr;</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-200 shadow-card">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-900">Actions rapides</h3>
                        </div>
                        <div class="p-5 space-y-3">
                            <a href="{{ route('offres.create') }}"
                               class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-brand-50 transition-colors group">
                                <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-white shadow-sm text-gray-600 group-hover:text-brand-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </span>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 group-hover:text-brand-700 transition-colors">Nouvelle offre</p>
                                    <p class="text-xs text-gray-500">Publier une nouvelle offre d'emploi</p>
                                </div>
                            </a>
                            @if ($hasOffres)
                                <a href="{{ route('offres.index') }}"
                                   class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-brand-50 transition-colors group">
                                    <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-white shadow-sm text-gray-600 group-hover:text-brand-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 group-hover:text-brand-700 transition-colors">Soumettre un CV</p>
                                        <p class="text-xs text-gray-500">Sélectionnez une offre et collez un CV</p>
                                    </div>
                                </a>
                                @if ($completedAnalyses >= 2)
                                    <a href="{{ route('candidats.index') }}"
                                       class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-brand-50 transition-colors group">
                                        <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-white shadow-sm text-gray-600 group-hover:text-brand-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                        </span>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 group-hover:text-brand-700 transition-colors">Comparer des candidats</p>
                                            <p class="text-xs text-gray-500">Classez et comparez les profils</p>
                                        </div>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
