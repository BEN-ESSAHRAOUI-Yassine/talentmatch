<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Répertoire des candidats
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-200 shadow-card">
                <div class="p-6">
                    @if ($candidats->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">CV</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Soumis le</th>
                                        <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($candidats as $candidat)
                                        <tr class="hover:bg-gray-50"
                                            x-data="{
                                                open: false,
                                                selectedOffre: '',
                                                candidateName: @js($candidat->name),
                                                candidateCv: @js($candidat->cv_text),
                                                baseUrl: @js(route('candidats.create', '_OFFRE_')),
                                                reuseUrl() {
                                                    if (!this.selectedOffre) return '#';
                                                    return this.baseUrl.replace('_OFFRE_', this.selectedOffre)
                                                        + '?name=' + encodeURIComponent(this.candidateName)
                                                        + '&cv_text=' + encodeURIComponent(this.candidateCv);
                                                }
                                            }">
                                            <td class="px-3 py-4 whitespace-nowrap font-medium text-gray-900">
                                                {{ $candidat->name }}
                                            </td>
                                            <td class="px-3 py-4 text-sm text-gray-600 max-w-sm truncate">
                                                {{ Str::limit($candidat->cv_text, 200) }}
                                            </td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $candidat->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-3 py-4 whitespace-nowrap text-right text-sm relative">
                                                <button @click="open = ! open"
                                                        class="inline-flex items-center px-3 py-1.5 bg-brand-600 text-white text-xs font-semibold rounded-lg hover:bg-brand-700 transition-all active:scale-[0.98]">
                                                    Réutiliser
                                                </button>
                                                <div x-show="open"
                                                     @click.outside="open = false"
                                                     x-cloak
                                                     class="absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg border border-gray-200 z-10">
                                                    <div class="p-3">
                                                        <p class="text-xs text-gray-500 mb-2">Soumettre ce CV à une offre :</p>
                                                        <select x-model="selectedOffre"
                                                                class="block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 mb-2">
                                                            <option value="">Choisir une offre...</option>
                                                            @foreach ($offres as $offre)
                                                                <option value="{{ $offre->id }}">{{ $offre->title }}</option>
                                                            @endforeach
                                                        </select>
                                                        <a x-bind:href="reuseUrl()"
                                                           class="block w-full text-center px-3 py-1.5 bg-brand-600 text-white text-xs font-semibold rounded-lg hover:bg-brand-700 transition-all active:scale-[0.98]"
                                                           x-bind:class="selectedOffre ? '' : 'opacity-50 pointer-events-none'">
                                                            Aller au formulaire
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">Aucun candidat dans le répertoire.</p>
                        <div class="text-center">
                            <a href="{{ route('offres.index') }}" class="inline-flex items-center px-4 py-2 bg-brand-600 text-white text-xs font-semibold rounded-xl hover:bg-brand-700 transition-all active:scale-[0.98]">
                                Voir les offres
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
