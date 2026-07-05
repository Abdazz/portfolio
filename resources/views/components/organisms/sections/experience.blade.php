@props(['experiences'])

@if ($experiences->isNotEmpty())
    <section class="py-20 md:py-28 lg:py-32">
        <div class="mx-auto max-w-4xl px-6 lg:px-8">
            <x-molecules.section-heading :eyebrow="__('home.experience_eyebrow')" :title="__('home.experience_title')" align="center" />

            <div class="mt-16" data-reveal>
                <x-organisms.experience-timeline :experiences="$experiences" />
            </div>
        </div>
    </section>
@endif
