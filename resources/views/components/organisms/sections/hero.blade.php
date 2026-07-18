@props(['profile'])

@php
    $locale = app()->getLocale();
    $headline = $profile?->getTranslation('headline', $locale) ?: __('home.hero_eyebrow');
    $bio = $profile?->getTranslation('bio', $locale) ?: __('home.hero_lead');
    $avatar = $profile?->getFirstMediaUrl('avatar') ?: null;
    $socials = collect($profile?->social_links ?? [])
        ->map(fn ($value, $key) => is_array($value)
            ? ['label' => $value['label'] ?? ucfirst((string) $key), 'url' => $value['url'] ?? null]
            : ['label' => ucfirst((string) $key), 'url' => $value])
        ->filter(fn ($s) => filled($s['url']))
        ->values();
@endphp

<section class="relative overflow-hidden pt-16 pb-20 md:pt-24 md:pb-28 lg:pt-28 lg:pb-32">
    {{-- Ambient purple glow --}}
    <div class="pointer-events-none absolute -top-40 -right-24 -z-10 h-[32rem] w-[32rem] rounded-full bg-accent/20 blur-[120px]" aria-hidden="true"></div>

    <div class="mx-auto grid max-w-7xl items-center gap-12 px-6 lg:grid-cols-[1.4fr_1fr] lg:gap-16 lg:px-8">
        <div class="flex flex-col gap-7">
            <span class="inline-flex items-center gap-2.5 self-start rounded-full border border-border bg-surface-muted px-4 py-2 text-sm font-medium text-accent-content" data-reveal>
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-green"></span>
                </span>
                {{ $headline }}
            </span>

            <h1 class="text-4xl font-semibold leading-[1.05] tracking-tight text-text sm:text-5xl lg:text-6xl xl:text-7xl" data-reveal>
                @if ($profile?->full_name)
                    {{ $profile->full_name }}
                @else
                    {{ config('app.name') }}
                @endif
            </h1>

            <p class="max-w-xl text-lg leading-relaxed text-text-muted" data-reveal>{{ $bio }}</p>

            <div class="flex flex-wrap items-center gap-4" data-reveal>
                <x-atoms.button href="{{ route('projects.index') }}" variant="primary" icon="arrow-up-right">
                    {{ __('home.cta_projects') }}
                </x-atoms.button>
                <x-atoms.button href="{{ route('resume') }}" variant="outline">
                    {{ __('home.cta_resume') }}
                </x-atoms.button>
            </div>

            @if ($socials->isNotEmpty())
                <div class="flex flex-wrap items-center gap-5 pt-2" data-reveal>
                    @foreach ($socials as $social)
                        <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer"
                           class="text-sm font-medium text-text-muted transition-colors hover:text-accent-content">
                            {{ $social['label'] ?? $social['url'] }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        @if ($avatar)
            <div class="relative mx-auto w-full max-w-sm lg:mx-0" data-reveal>
                <div class="absolute inset-0 -z-10 translate-x-4 translate-y-4 rounded-[2rem] gradient-primary opacity-30 blur-2xl" aria-hidden="true"></div>
                <img src="{{ $avatar }}" alt="{{ $profile?->full_name }}"
                     class="w-full rounded-[2rem] border border-border object-cover shadow-2xl shadow-accent/10"
                     width="480" height="560">
            </div>
        @endif
    </div>
</section>
