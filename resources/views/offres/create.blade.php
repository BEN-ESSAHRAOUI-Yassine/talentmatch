<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nouvelle offre') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('offres.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="title" value="Titre de l'offre" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Description" />
                            <textarea id="description" name="description" rows="6" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div x-data="{
                            skills: @js(old('required_skills', [])),
                            newSkill: '',
                            addSkill() {
                                const trimmed = this.newSkill.trim();
                                if (trimmed && !this.skills.includes(trimmed)) {
                                    this.skills.push(trimmed);
                                }
                                this.newSkill = '';
                            },
                            removeSkill(index) {
                                this.skills.splice(index, 1);
                            }
                        }">
                            <x-input-label for="required_skills" value="Compétences requises" />
                            <div class="mt-1 flex gap-2">
                                <input
                                    type="text"
                                    x-model="newSkill"
                                    @keydown.enter.prevent="addSkill"
                                    @keydown.,.prevent="addSkill"
                                    placeholder="Taper une compétence puis Entrée"
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <button type="button" @click="addSkill" class="inline-flex items-center px-3 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Ajouter
                                </button>
                            </div>
                            <template x-for="(skill, index) in skills" :key="index">
                                <span class="inline-flex items-center mt-2 mr-2 px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                    <span x-text="skill"></span>
                                    <button type="button" @click="removeSkill(index)" class="ml-2 text-indigo-600 hover:text-indigo-900">&times;</button>
                                    <input type="hidden" :name="`required_skills[${index}]`" :value="skill">
                                </span>
                            </template>
                            <x-input-error :messages="$errors->get('required_skills')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="minimum_experience" value="Expérience minimale (années)" />
                            <x-text-input id="minimum_experience" name="minimum_experience" type="number" min="0" class="mt-1 block w-48" :value="old('minimum_experience', 0)" required />
                            <x-input-error :messages="$errors->get('minimum_experience')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Créer l\'offre') }}</x-primary-button>
                            <a href="{{ route('offres.index') }}" class="text-sm text-gray-600 hover:underline">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
