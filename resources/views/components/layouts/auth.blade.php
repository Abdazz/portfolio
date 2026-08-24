<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-dvh bg-surface text-text antialiased font-sans flex items-center justify-center p-4">

    <div class="w-full max-w-sm space-y-6">
        <a href="{{ route('home') }}" class="flex justify-center">
            <span class="font-display text-lg font-semibold tracking-wider text-text hover:text-accent transition-colors">
                {{ config('app.name') }}
            </span>
        </a>

        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
