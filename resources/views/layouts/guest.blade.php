<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TalentMatch') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-surface-muted">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-brand-600 text-white text-sm font-bold">TM</span>
                <span class="text-xl font-bold text-gray-900">TalentMatch</span>
            </div>

            <div class="w-full sm:max-w-md mt-2 px-6 py-6 bg-white rounded-2xl shadow-card border border-gray-200">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
