<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'TalentMatch') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col bg-gradient-to-br from-gray-50 to-gray-100">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4 px-6 py-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none transition">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif

            <div class="flex-1 flex flex-col items-center justify-center px-6">
                <div class="max-w-2xl text-center">
                    <h1 class="text-5xl font-bold text-gray-900 mb-4">TalentMatch</h1>
                    <p class="text-xl text-gray-600 mb-8">Automatisez la présélection de vos candidats grâce à l'intelligence artificielle.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-left">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="text-3xl mb-2">📋</div>
                            <h3 class="font-semibold text-gray-900 mb-1">Offres d'emploi</h3>
                            <p class="text-sm text-gray-500">Créez et gérez vos offres en quelques clics.</p>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="text-3xl mb-2">🤖</div>
                            <h3 class="font-semibold text-gray-900 mb-1">Analyse IA</h3>
                            <p class="text-sm text-gray-500">Soumettez un CV, l'IA l'analyse et le score automatiquement.</p>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="text-3xl mb-2">💬</div>
                            <h3 class="font-semibold text-gray-900 mb-1">Agent conversationnel</h3>
                            <p class="text-sm text-gray-500">Posez des questions sur un candidat et obtenez des réponses contextuelles.</p>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="py-6 text-center text-sm text-gray-400">
                <p>{{ config('app.name', 'TalentMatch') }} &mdash; Laravel {{ app()->version() }}</p>
            </footer>
        </div>
    </body>
</html>
