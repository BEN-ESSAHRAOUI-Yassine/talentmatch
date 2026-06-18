<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mes offres') }}
            </h2>
            <a href="{{ route('offres.create') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 transition-all active:scale-[0.98]">
                + Nouvelle offre
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form method="GET" action="{{ route('offres.index') }}" class="mb-6">
                <div class="flex gap-2">
                    <x-text-input
                        type="text"
                        name="search"
                        placeholder="Rechercher par titre..."
                        value="{{ request('search') }}"
                        class="w-full"
                    />
                                                <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 transition-all active:scale-[0.98] shrink-0">Rechercher</button>
                </div>
            </form>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-card">
                <div class="p-6">
                    @if ($offres->isEmpty())
                        <p class="text-gray-500 text-center py-8">
                            @if ($search)
                                Aucune offre trouvée pour "{{ $search }}".
                            @else
                                Vous n'avez pas encore créé d'offre.
                                <a href="{{ route('offres.create') }}" class="text-indigo-600 hover:underline">Créer votre première offre</a>.
                            @endif
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Compétences requises</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exp. min.</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($offres as $offre)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                                {{ $offre->title }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach ($offre->required_skills as $skill)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                                            {{ $skill }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $offre->minimum_experience }} an(s)
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $offre->created_at->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('offres.show', $offre) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Voir</a>
                                                <a href="{{ route('offres.edit', $offre) }}" class="text-amber-600 hover:text-amber-900 mr-3">Modifier</a>
                                                <form action="{{ route('offres.destroy', $offre) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette offre ? Les analyses associées seront également supprimées.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $offres->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
