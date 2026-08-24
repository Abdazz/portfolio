<x-layouts.public :title="__('projects.title')">

    <div class="space-y-10">
        <div class="space-y-6 animate-enter">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-accent">{{ __('nav.projects') }}</p>
            <h1 class="font-display font-semibold leading-tight text-text" style="font-size: clamp(2.25rem, 5vw, 4rem); font-optical-sizing: auto;">
                {{ __('projects.title') }}
            </h1>
            <p class="max-w-xl text-base text-text-muted leading-relaxed">{{ __('projects.subtitle') }}</p>
        </div>

        <div class="border-t border-border/50 pt-10 animate-enter animate-enter-delay-2">
            <livewire:projects.project-filters />
        </div>
    </div>

</x-layouts.public>
