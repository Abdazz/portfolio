@props([
    'title'       => null,
    'description' => null,
    'ogImage'     => null,
    'head'        => null,
])

@php
    use App\Models\SiteSetting;
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

    $themeCookie = request()->cookie('theme');
    $isDark      = $themeCookie !== 'light'; // dark by default
    $locale      = app()->getLocale();
    $locales     = LaravelLocalization::getSupportedLocales();

    $pageTitle    = filled($title) ? $title.' — '.config('app.name') : config('app.name');
    $canonicalUrl = url()->current();

    $settings = SiteSetting::instance();
    $resolvedOgImage = $ogImage ?? $settings->ogImageUrl($locale);
    $twitterHandle   = $settings->twitter_handle;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}" class="{{ $isDark ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $pageTitle }}</title>
    @if (filled($description))
        <meta name="description" content="{{ $description }}">
    @endif

    {{-- Open Graph --}}
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="{{ str_replace('-', '_', app()->getLocale() === 'fr' ? 'fr_FR' : 'en_US') }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    @if (filled($description))
        <meta property="og:description" content="{{ $description }}">
    @endif
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:type" content="website">
    @if (filled($resolvedOgImage))
        <meta property="og:image" content="{{ $resolvedOgImage }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
    @endif

    {{-- Twitter / X Card --}}
    <meta name="twitter:card" content="summary_large_image">
    @if (filled($twitterHandle))
        <meta name="twitter:site" content="@{{ $twitterHandle }}">
    @endif
    <meta name="twitter:title" content="{{ $pageTitle }}">
    @if (filled($description))
        <meta name="twitter:description" content="{{ $description }}">
    @endif
    @if (filled($resolvedOgImage))
        <meta name="twitter:image" content="{{ $resolvedOgImage }}">
    @endif

    {{-- Hreflang --}}
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="sitemap" type="application/xml" href="/sitemap.xml">
    @foreach ($locales as $altLocale => $properties)
        <link rel="alternate" hreflang="{{ $altLocale }}" href="{{ LaravelLocalization::getLocalizedURL($altLocale, null, [], true) }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}">

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    {{ $head ?? '' }}
</head>
<body class="min-h-dvh bg-surface text-text antialiased font-sans selection:bg-accent/20 selection:text-accent">

    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:p-4 focus:bg-accent focus:text-accent-foreground">
        {{ __('Skip to content') }}
    </a>

    <header
        x-data="{ open: false, scrolled: false }"
        @scroll.window="scrolled = window.scrollY > 30"
        class="sticky top-0 z-40 transition-all duration-300"
        :class="scrolled ? 'bg-surface/90 backdrop-blur-md border-b border-border/50 shadow-sm' : 'bg-transparent'"
    >
        <div class="mx-auto flex max-w-5xl items-center justify-between gap-4 px-6 py-4">

            <a href="{{ route('home') }}" class="font-display text-sm font-semibold tracking-wider text-text hover:text-accent transition-colors" style="font-optical-sizing: auto;">
                {{ config('app.name') }}
            </a>

            <nav aria-label="{{ __('nav.aria') }}" class="hidden items-center gap-7 sm:flex">
                <a href="{{ route('home') }}"
                   class="text-xs font-semibold uppercase tracking-widest transition-colors {{ request()->routeIs('home') ? 'text-accent' : 'text-text-muted hover:text-text' }}">
                    {{ __('nav.home') }}
                </a>
                <a href="{{ route('projects.index') }}"
                   class="text-xs font-semibold uppercase tracking-widest transition-colors {{ request()->routeIs('projects.*') ? 'text-accent' : 'text-text-muted hover:text-text' }}">
                    {{ __('nav.projects') }}
                </a>
                <a href="{{ route('resume') }}"
                   class="text-xs font-semibold uppercase tracking-widest transition-colors {{ request()->routeIs('resume*') ? 'text-accent' : 'text-text-muted hover:text-text' }}">
                    {{ __('nav.resume') }}
                </a>
                <a href="{{ route('contact') }}"
                   class="text-xs font-semibold uppercase tracking-widest transition-colors {{ request()->routeIs('contact') ? 'text-accent' : 'text-text-muted hover:text-text' }}">
                    {{ __('nav.contact') }}
                </a>
            </nav>

            <div class="flex items-center gap-3">
                <x-molecules.language-switcher />

                <button
                    type="button"
                    aria-label="{{ __('nav.toggle_theme') }}"
                    x-data
                    @click="
                        const html = document.documentElement;
                        const isDark = html.classList.toggle('dark');
                        document.cookie = 'theme=' + (isDark ? 'dark' : 'light') + '; path=/; max-age=31536000; SameSite=Lax';
                    "
                    class="rounded p-1.5 text-text-muted hover:text-accent transition-colors"
                >
                    <flux:icon name="sun" class="size-4 dark:hidden" />
                    <flux:icon name="moon" class="size-4 hidden dark:block" />
                </button>

                <button
                    type="button"
                    aria-label="{{ __('nav.aria') }}"
                    @click="open = !open"
                    class="sm:hidden rounded p-1.5 text-text-muted hover:text-accent transition-colors"
                >
                    <flux:icon name="bars-3" class="size-5" />
                </button>
            </div>
        </div>

        <div
            x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            class="sm:hidden border-t border-border bg-surface px-6 py-5 space-y-4"
        >
            <a href="{{ route('home') }}" class="block text-xs font-semibold uppercase tracking-widest {{ request()->routeIs('home') ? 'text-accent' : 'text-text-muted hover:text-text' }} transition-colors">
                {{ __('nav.home') }}
            </a>
            <a href="{{ route('projects.index') }}" class="block text-xs font-semibold uppercase tracking-widest {{ request()->routeIs('projects.*') ? 'text-accent' : 'text-text-muted hover:text-text' }} transition-colors">
                {{ __('nav.projects') }}
            </a>
            <a href="{{ route('resume') }}" class="block text-xs font-semibold uppercase tracking-widest {{ request()->routeIs('resume*') ? 'text-accent' : 'text-text-muted hover:text-text' }} transition-colors">
                {{ __('nav.resume') }}
            </a>
            <a href="{{ route('contact') }}" class="block text-xs font-semibold uppercase tracking-widest {{ request()->routeIs('contact') ? 'text-accent' : 'text-text-muted hover:text-text' }} transition-colors">
                {{ __('nav.contact') }}
            </a>
        </div>
    </header>

    <main id="main-content" class="mx-auto max-w-5xl px-6 py-12">
        {{ $slot }}
    </main>

    <footer class="mt-20 border-t border-border/50">
        <div class="mx-auto max-w-5xl px-6 py-10">
            <div class="flex flex-col gap-8 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="font-display text-base font-semibold text-text" style="font-optical-sizing: auto;">{{ config('app.name') }}</p>
                    <p class="mt-1 text-xs uppercase tracking-widest text-text-muted">&copy; {{ now()->year }}</p>
                </div>
                <nav aria-label="{{ __('Footer navigation') }}" class="flex flex-wrap items-center gap-6">
                    <a href="{{ route('projects.index') }}" class="text-xs font-semibold uppercase tracking-widest text-text-muted hover:text-accent transition-colors">{{ __('nav.projects') }}</a>
                    <a href="{{ route('resume') }}" class="text-xs font-semibold uppercase tracking-widest text-text-muted hover:text-accent transition-colors">{{ __('nav.resume') }}</a>
                    <a href="{{ route('contact') }}" class="text-xs font-semibold uppercase tracking-widest text-text-muted hover:text-accent transition-colors">{{ __('nav.contact') }}</a>
                    <a href="{{ route('resume.json') }}" class="text-xs font-semibold uppercase tracking-widest text-text-muted hover:text-accent transition-colors">JSON</a>
                </nav>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
