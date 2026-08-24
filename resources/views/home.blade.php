<x-layouts.public :title="__('nav.home')">
    <x-slot:head>
        <x-atoms.json-ld type="Person" :profile="$profile" />
    </x-slot:head>

    {{-- Hero --}}
    <section class="py-12 sm:py-20 lg:py-28">
        <div class="space-y-8">
            <p class="animate-enter text-xs font-semibold uppercase tracking-[0.25em] text-accent">
                {{ $profile?->getTranslation('headline', app()->getLocale()) ?: __('home.hero_eyebrow') }}
            </p>

            <h1 class="animate-enter animate-enter-delay-1 font-display font-semibold leading-none tracking-tight text-text" style="font-size: clamp(3rem, 8vw, 7rem); font-optical-sizing: auto;">
                @if ($profile?->full_name)
                    {!! nl2br(e($profile->full_name)) !!}
                @else
                    {{ config('app.name') }}
                @endif
            </h1>

            <p class="animate-enter animate-enter-delay-2 max-w-xl text-base text-text-muted leading-relaxed sm:text-lg">
                {{ $profile?->getTranslation('bio', app()->getLocale()) ?: __('home.hero_lead') }}
            </p>

            <div class="animate-enter animate-enter-delay-3 flex flex-wrap items-center gap-3">
                <x-atoms.button href="{{ route('projects.index') }}" variant="primary" icon="folder-git-2">
                    {{ __('home.cta_projects') }}
                </x-atoms.button>
                <x-atoms.button href="{{ route('resume') }}" variant="secondary" icon="book-open-text">
                    {{ __('home.cta_resume') }}
                </x-atoms.button>
            </div>
        </div>
    </section>

    {{-- Divider --}}
    <div class="border-t border-border/50"></div>

    {{-- Featured projects --}}
    @if ($featuredProjects->isNotEmpty())
        <section class="animate-enter animate-enter-delay-4 space-y-8 pt-16">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-baseline gap-4">
                    <span class="font-display text-xs font-semibold text-accent" style="font-optical-sizing: auto;">01</span>
                    <span class="text-border/60">—</span>
                    <h2 class="font-display text-lg font-semibold uppercase tracking-widest text-text" style="font-optical-sizing: auto;">{{ __('home.featured_title') }}</h2>
                </div>
                <x-atoms.link href="{{ route('projects.index') }}">
                    {{ __('home.featured_more') }} →
                </x-atoms.link>
            </div>
            <x-organisms.project-card-grid :projects="$featuredProjects" />
        </section>
    @endif

</x-layouts.public>
