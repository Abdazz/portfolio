<x-layouts.public :title="__('projects.title')">

    <section class="py-20 md:py-28 lg:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex flex-col gap-5" data-reveal>
                <span class="inline-flex items-center gap-2.5 text-sm font-medium uppercase tracking-[0.2em] text-accent-content">
                    <span class="h-px w-8 bg-accent/50" aria-hidden="true"></span>
                    {{ __('nav.projects') }}
                </span>
                <h1 class="gradient-text max-w-3xl text-4xl font-semibold leading-[1.1] md:text-5xl lg:text-6xl">
                    {{ __('projects.title') }}
                </h1>
                <p class="max-w-xl text-base leading-relaxed text-text-muted">{{ __('projects.subtitle') }}</p>
            </div>

            <div class="mt-14 border-t border-border/50 pt-12" data-reveal>
                <livewire:projects.project-filters />
            </div>
        </div>
    </section>

</x-layouts.public>
