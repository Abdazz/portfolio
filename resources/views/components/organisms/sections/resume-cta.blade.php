@props(['profile' => null])

<section class="py-20 md:py-28 lg:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-[2rem] border border-border bg-surface-muted px-8 py-14 md:px-16 md:py-20" data-reveal>
            <div class="pointer-events-none absolute -bottom-24 -left-16 -z-0 h-72 w-72 rounded-full bg-accent/15 blur-[100px]" aria-hidden="true"></div>

            <div class="relative flex flex-col items-start gap-8 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex max-w-2xl flex-col gap-4">
                    <x-molecules.section-heading :eyebrow="__('home.resume_eyebrow')" :title="__('home.resume_title')" />
                    <p class="text-lg leading-relaxed text-text-muted">{{ __('home.resume_lead') }}</p>
                </div>

                <div class="flex shrink-0 flex-wrap gap-4">
                    <x-atoms.button href="{{ route('resume') }}" variant="primary" icon="arrow-up-right">
                        {{ __('home.resume_view') }}
                    </x-atoms.button>
                    <x-atoms.button href="{{ route('resume.download') }}" variant="outline">
                        {{ __('home.resume_download') }}
                    </x-atoms.button>
                </div>
            </div>
        </div>
    </div>
</section>
