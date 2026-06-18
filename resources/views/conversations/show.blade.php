<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Conversation — {{ $analyse->candidat->name }}
            </h2>
            <a href="{{ route('candidats.show', [$analyse->offre_id, $analyse->candidat_id]) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                &larr; Retour à l'analyse
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($conversation->messages->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-500">Aucun message pour le moment. Envoyez votre premier message ci-dessous.</p>
                        </div>
                    @else
                        <div class="space-y-4 mb-6 max-h-96 overflow-y-auto" x-ref="messages">
                            @foreach ($conversation->messages as $message)
                                <div class="flex {{ $message->role === \App\Enums\MessageRoleEnum::User ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[75%] rounded-lg px-4 py-3 text-sm leading-relaxed {{ $message->role === \App\Enums\MessageRoleEnum::User ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-900' }}">
                                        <div class="prose prose-sm max-w-none {{ $message->role === \App\Enums\MessageRoleEnum::User ? 'prose-invert' : '' }}">
                                            {!! Str::of($message->content)->markdown() !!}
                                        </div>
                                        <p class="text-xs mt-2 {{ $message->role === \App\Enums\MessageRoleEnum::User ? 'text-indigo-200' : 'text-gray-400' }}">
                                            {{ $message->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('messages.store', [$analyse, $conversation]) }}" class="border-t border-gray-200 pt-4">
                        @csrf
                        <div class="flex gap-2">
                            <textarea
                                name="content"
                                rows="2"
                                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Posez une question sur cette analyse..."
                                required
                            ></textarea>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                                Envoyer
                            </button>
                        </div>
                        @error('content')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
