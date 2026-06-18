<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Soumettre un CV — {{ $offre->title }}
            </h2>
            <a href="{{ route('offres.show', $offre) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 transition-all active:scale-[0.98]">
                &larr; Retour
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-card">
                <div class="p-6">
                    <form action="{{ route('candidats.store', $offre) }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nom du candidat</label>
                            <input type="text" name="name" id="name" value="{{ old('name', request('name')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Ex: Jean Dupont">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="cv_text" class="block text-sm font-medium text-gray-700">Texte du CV</label>
                            <textarea name="cv_text" id="cv_text" rows="12"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Collez le contenu du CV ici (minimum 50 caractères)...">{{ old('cv_text', request('cv_text')) }}</textarea>
                            @error('cv_text')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('offres.show', $offre) }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-all active:scale-[0.98]">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-all active:scale-[0.98]">
                                Soumettre
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
