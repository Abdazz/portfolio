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
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-6 py-4 lg:px-8 xl:py-5">

                <ul class="flex items-center gap-x-[15px] xl:gap-x-[35px]">
                    <li class="leading-none">
                        <a href="{{ route('home') }}" class="text-lg font-semibold tracking-tight text-text transition-colors hover:text-accent-content">
                            {{ config('app.name') }}<span class="text-accent">.</span>
                        </a>
                    </li>
                    @if (filled($settings->contact_email))
                        <li class="hidden md:block">
                            <a href="mailto:{{ $settings->contact_email }}" class="text-[15px] font-medium text-accent-deep dark:text-white">
                                {{ $settings->contact_email }}
                            </a>
                        </li>
                    @endif
                </ul>

                <nav aria-label="{{ __('nav.aria') }}" class="hidden items-center md:flex">
                    <ul class="flex items-center gap-x-5 xl:gap-x-[30px]">
                        @foreach ($navItems as $item)
                            <li class="group relative">
                                <a href="{{ route($item['route']) }}"
                                   @if (request()->routeIs($item['active'])) aria-current="page" @endif
                                   class="relative z-0 py-[10px] text-[15px] font-medium capitalize text-accent-deep after:absolute after:bottom-0 after:right-0 after:h-0.5 after:gradient-primary after:transition-all after:duration-500 group-hover:after:left-0 group-hover:after:w-full dark:text-white {{ request()->routeIs($item['active']) ? 'after:left-0 after:w-full' : 'after:w-0' }}">
                                    {{ $item['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
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

                    <a href="{{ route('contact') }}"
                       class="ml-[10px] hidden rounded-full gradient-secondary px-[35px] py-[17px] text-[15px] font-bold capitalize leading-none text-white transition-all duration-300 hover:[background-position:-100%_0] lg:inline-block">
                        {{ __('nav.hire_me') }}
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

    <footer class="bg-accent-deep dark:bg-surface">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex flex-col items-center pt-[50px] pb-5 md:pt-[60px]">
                <a href="{{ route('home') }}" class="mb-6 text-2xl font-semibold tracking-tight text-white">
                    {{ config('app.name') }}<span class="text-accent">.</span>
                </a>

                <nav aria-label="{{ __('Footer navigation') }}">
                    <ul class="flex flex-wrap items-center justify-center gap-x-[35px]">
                        @foreach ($navItems as $item)
                            <li class="group relative">
                                <a href="{{ route($item['route']) }}"
                                   class="relative z-0 inline-block py-[10px] text-[15px] font-medium capitalize text-white after:absolute after:bottom-[5px] after:right-0 after:h-0.5 after:w-0 after:gradient-primary after:transition-all after:duration-500 group-hover:after:left-0 group-hover:after:w-full md:py-[15px]">
                                    {{ $item['label'] }}
                                </a>
                            </li>
                        @endforeach
                        <li class="group relative">
                            <a href="{{ route('resume.json') }}"
                               class="relative z-0 inline-block py-[10px] text-[15px] font-medium text-white after:absolute after:bottom-[5px] after:right-0 after:h-0.5 after:w-0 after:gradient-primary after:transition-all after:duration-500 group-hover:after:left-0 group-hover:after:w-full md:py-[15px]">
                                JSON
                            </a>
                        </li>
                    </ul>
                </nav>

                <p class="mt-5 whitespace-nowrap text-sm text-[#636363] md:text-base">
                    &copy; {{ now()->year }} {{ __('All rights reserved.') }}
                    <a href="{{ route('home') }}" class="text-white transition-colors hover:text-accent">{{ config('app.name') }}</a>
                </p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
