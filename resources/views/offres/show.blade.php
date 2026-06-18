<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $offre->title }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('offres.edit', $offre) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-400">
                    Modifier
                </a>
                <form action="{{ route('offres.destroy', $offre) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette offre ? Les analyses associées seront également supprimées.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
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
        </div>
    </div>
</x-app-layout>
