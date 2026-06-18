<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <a href="{{ route('offres.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <div class="text-3xl font-bold text-gray-900">{{ auth()->user()->offres()->count() }}</div>
                        <div class="text-sm text-gray-600 mt-1">Offre(s) d'emploi</div>
                        <div class="text-xs text-indigo-600 mt-2">Gérer les offres &rarr;</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
