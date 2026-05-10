@props([
    'title' => null,
    'description' => null,
])

@php
    $themeCookie = request()->cookie('theme');
    $isDark = $themeCookie === 'dark';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $isDark ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ filled($title) ? $title.' — '.config('app.name') : config('app.name') }}</title>
    @if (filled($description))
        <meta name="description" content="{{ $description }}">
    @endif

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-dvh bg-surface text-text antialiased font-sans">
    <header class="border-b border-border">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-4">
            <a href="{{ route('home') }}" class="font-display text-lg font-semibold text-accent">
                {{ config('app.name') }}
            </a>
            <nav aria-label="{{ __('Main navigation') }}" class="text-sm text-text-muted">
                <span class="uppercase tracking-wide">{{ app()->getLocale() }}</span>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-6 py-12">
        {{ $slot }}
    </main>

    <footer class="mt-16 border-t border-border">
        <div class="mx-auto max-w-5xl px-6 py-6 text-sm text-text-muted">
            &copy; {{ now()->year }} {{ config('app.name') }}
        </div>
    </footer>
</body>
</html>
