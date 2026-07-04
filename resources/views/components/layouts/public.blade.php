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

    $navItems = [
        ['route' => 'home', 'active' => 'home', 'label' => __('nav.home')],
        ['route' => 'projects.index', 'active' => 'projects.*', 'label' => __('nav.projects')],
        ['route' => 'resume', 'active' => 'resume*', 'label' => __('nav.resume')],
        ['route' => 'contact', 'active' => 'contact', 'label' => __('nav.contact')],
    ];
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

    <link rel="stylesheet" href="{{ asset('vendor/gerold/css/font-awesome-pro.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/gerold/css/flaticon_gerold.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    {{ $head ?? '' }}
</head>
<body class="min-h-dvh bg-surface text-text antialiased font-sans selection:bg-accent/20 selection:text-accent">

    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:m-4 focus:rounded-full focus:px-5 focus:py-3 focus:gradient-primary focus:text-white">
        {{ __('Skip to content') }}
    </a>

    <header
        x-data="{ open: false, atTop: true, hidden: false, lastY: 0 }"
        x-init="lastY = window.scrollY"
        @scroll.window="
            const y = window.scrollY;
            atTop = y < 30;
            hidden = !open && y > lastY && y > 240;
            lastY = y;
        "
        class="sticky top-0 z-40 transition-transform duration-500"
        :class="hidden ? '-translate-y-full' : 'translate-y-0'"
    >
        <div
            class="border-b transition-colors duration-300"
            :class="atTop && !open ? 'border-transparent bg-transparent' : 'border-border/60 bg-surface/85 backdrop-blur-lg'"
        >
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-6 py-5 lg:px-8">

                <a href="{{ route('home') }}" class="text-lg font-semibold tracking-tight text-text transition-colors hover:text-accent-content">
                    {{ config('app.name') }}<span class="text-accent">.</span>
                </a>

                <nav aria-label="{{ __('nav.aria') }}" class="hidden items-center gap-9 md:flex">
                    @foreach ($navItems as $item)
                        <a href="{{ route($item['route']) }}"
                           @if (request()->routeIs($item['active'])) aria-current="page" @endif
                           class="text-sm font-medium transition-colors {{ request()->routeIs($item['active']) ? 'text-accent-content' : 'text-text-muted hover:text-text' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="flex items-center gap-2">
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
                        class="rounded-full p-2 text-text-muted transition-colors hover:bg-surface-muted hover:text-accent-content"
                    >
                        <flux:icon name="sun" class="size-4 dark:hidden" />
                        <flux:icon name="moon" class="hidden size-4 dark:block" />
                    </button>

                    <a href="{{ route('contact') }}" class="hidden lg:inline-flex">
                        <x-atoms.button variant="primary" size="sm">{{ __('nav.contact') }}</x-atoms.button>
                    </a>

                    <button
                        type="button"
                        aria-label="{{ __('nav.aria') }}"
                        :aria-expanded="open.toString()"
                        @click="open = !open"
                        class="rounded-full p-2 text-text-muted transition-colors hover:bg-surface-muted hover:text-accent-content md:hidden"
                    >
                        <flux:icon name="bars-3" class="size-5" x-show="!open" />
                        <flux:icon name="x-mark" class="size-5" x-show="open" x-cloak />
                    </button>
                </div>
            </div>
        </div>

        <div
            x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="space-y-1 border-b border-border bg-surface px-6 py-4 md:hidden"
        >
            @foreach ($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   @click="open = false"
                   class="block rounded-xl px-4 py-3 text-base font-medium transition-colors {{ request()->routeIs($item['active']) ? 'bg-surface-muted text-accent-content' : 'text-text-muted hover:bg-surface-muted hover:text-text' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </header>

    <main id="main-content">
        {{ $slot }}
    </main>

    <footer class="border-t border-border/60 bg-surface-deep/40">
        <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
            <div class="flex flex-col gap-10 md:flex-row md:items-start md:justify-between">
                <div class="max-w-sm">
                    <a href="{{ route('home') }}" class="text-xl font-semibold tracking-tight text-text">
                        {{ config('app.name') }}<span class="text-accent">.</span>
                    </a>
                    @if (filled($settings->contact_email))
                        <a href="mailto:{{ $settings->contact_email }}" class="mt-3 block text-sm text-text-muted transition-colors hover:text-accent-content">
                            {{ $settings->contact_email }}
                        </a>
                    @endif
                </div>

                <nav aria-label="{{ __('Footer navigation') }}" class="flex flex-wrap gap-x-8 gap-y-3">
                    @foreach ($navItems as $item)
                        <a href="{{ route($item['route']) }}" class="text-sm font-medium text-text-muted transition-colors hover:text-accent-content">{{ $item['label'] }}</a>
                    @endforeach
                    <a href="{{ route('resume.json') }}" class="text-sm font-medium text-text-muted transition-colors hover:text-accent-content">JSON</a>
                </nav>
            </div>

            <div class="mt-12 border-t border-border/60 pt-8">
                <p class="text-xs text-text-muted">&copy; {{ now()->year }} {{ config('app.name') }}. {{ __('All rights reserved.') }}</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
