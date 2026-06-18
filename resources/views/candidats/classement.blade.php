<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Classement — {{ $offre->title }}
            </h2>
            <a href="{{ route('offres.show', $offre) }}" class="text-sm text-brand-600 hover:text-brand-700 font-medium">
                &larr; Retour à l'offre
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <nav class="mb-4 text-sm text-gray-500">
                <a href="{{ route('offres.index') }}" class="hover:text-brand-600">Offres</a>
                <span class="mx-2">&rsaquo;</span>
                <a href="{{ route('offres.show', $offre) }}" class="hover:text-brand-600">{{ $offre->title }}</a>
                <span class="mx-2">&rsaquo;</span>
                <span class="text-gray-900">Classement</span>
            </nav>

            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-200 shadow-card"
                 x-data="{
                     selectedIds: [],
                     toggle(id) {
                         if (this.selectedIds.includes(id)) {
                             this.selectedIds = this.selectedIds.filter(i => i !== id);
                         } else if (this.selectedIds.length < 2) {
                             this.selectedIds.push(id);
                         }
                     },
                     isSelected(id) {
                         return this.selectedIds.includes(id);
                     },
                     get canCompare() {
                         return this.selectedIds.length === 2;
                     }
                 }">
                <div class="p-6">
                    @if ($analyses->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase w-10"></th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Candidat</th>
                                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase">Score</th>
                                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase">Recommandation</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Points forts</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lacunes</th>
                                        <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($analyses as $analyse)
                                        @php $isCompleted = $analyse->status->value === 'completed'; @endphp
                                        <tr class="hover:bg-gray-50 {{ !$isCompleted ? 'opacity-60' : '' }}">
                                            <td class="px-3 py-4 whitespace-nowrap">
                                                @if ($isCompleted)
                                                    <input type="checkbox"
                                                           value="{{ $analyse->candidat_id }}"
                                                           x-on:change="toggle({{ $analyse->candidat_id }})"
                                                           x-bind:checked="isSelected({{ $analyse->candidat_id }})"
                                                            class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                                @endif
                                            </td>
                                            <td class="px-3 py-4 whitespace-nowrap">
                                                <span class="font-medium text-gray-900">{{ $analyse->candidat->name }}</span>
                                            </td>
                                            <td class="px-3 py-4 whitespace-nowrap text-center">
                                                @if ($isCompleted)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-semibold
                                                        {{ $analyse->matching_score >= 70 ? 'bg-green-100 text-green-800' : ($analyse->matching_score >= 40 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                        {{ $analyse->matching_score }}%
                                                    </span>
                                                @elseif ($analyse->status->value === 'pending')
                                                    <span class="inline-flex items-center text-sm text-gray-400">
                                                        <svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                        </svg>
                                                        Analyse en cours…
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">Échec</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-4 whitespace-nowrap text-center">
                                                @if ($isCompleted)
                                                    @php
                                                        $rec = $analyse->recommandation->value ?? $analyse->recommandation;
                                                    @endphp
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $rec === 'convoquer' ? 'bg-green-100 text-green-800' : ($rec === 'attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                        {{ $rec === 'convoquer' ? 'Convoquer' : ($rec === 'attente' ? 'En attente' : 'Rejeter') }}
                                                    </span>
                                                @elseif ($analyse->status->value === 'pending')
                                                    <span class="text-gray-300 text-sm">&mdash;</span>
                                                @else
                                                    <span class="text-gray-300 text-sm">&mdash;</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-4 text-sm text-gray-600 max-w-xs truncate">
                                                @if ($isCompleted && $analyse->points_forts)
                                                    {{ implode(', ', array_slice($analyse->points_forts, 0, 3)) }}
                                                @else
                                                    <span class="text-gray-300">&mdash;</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-4 text-sm text-gray-600 max-w-xs truncate">
                                                @if ($isCompleted && $analyse->lacunes)
                                                    {{ implode(', ', array_slice($analyse->lacunes, 0, 3)) }}
                                                @else
                                                    <span class="text-gray-300">&mdash;</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-4 whitespace-nowrap text-right text-sm">
                                                @if ($isCompleted)
                                                    <a href="{{ route('candidats.show', [$offre, $analyse->candidat]) }}" class="text-brand-600 hover:text-brand-700 font-medium">Voir</a>
                                                @elseif ($analyse->status->value === 'failed')
                                                    <form action="{{ route('analyses.retry', $analyse) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-amber-600 hover:text-amber-900">Réanalyser</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($analyse->status->value === 'failed' && $analyse->error_message)
                                        <tr class="border-t-0">
                                            <td colspan="7" class="px-3 pb-3 text-xs text-red-600">
                                                {{ $analyse->error_message }}
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @php $completedCount = $analyses->filter(fn ($a) => $a->status->value === 'completed')->count(); @endphp
                        @if ($completedCount >= 2)
                        <div class="mt-4 flex justify-center"
                             x-show="canCompare"
                             x-cloak>
                             <a x-bind:href="'{{ route('candidats.comparer', $offre) }}?ids[]=' + selectedIds[0] + '&ids[]=' + selectedIds[1]"
                                class="inline-flex items-center px-6 py-3 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-all active:scale-[0.98]">
                                 Comparer les 2 candidats sélectionnés
                             </a>
                        </div>
                        @endif
                    @else
                        <p class="text-gray-500 text-center py-8">Aucun candidat soumis pour cette offre.</p>
                        <div class="text-center">
                            <a href="{{ route('candidats.create', $offre) }}" class="inline-flex items-center px-4 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-all active:scale-[0.98]">
                                + Soumettre un CV
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
