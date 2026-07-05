@props(['skills'])

@php
    $locale = app()->getLocale();
    $groups = $skills->groupBy('category');
@endphp

@if ($skills->isNotEmpty())
    <section class="py-20 md:py-28 lg:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <x-molecules.section-heading :eyebrow="__('home.skills_eyebrow')" :title="__('home.skills_title')" />

            <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($groups as $category => $categorySkills)
                    <x-molecules.card class="flex flex-col gap-5" data-reveal>
                        @if (filled($category))
                            <h3 class="text-sm font-semibold uppercase tracking-[0.15em] text-accent-content">{{ $category }}</h3>
                        @endif
                        <div class="flex flex-wrap gap-2.5">
                            @foreach ($categorySkills as $skill)
                                <span class="inline-flex items-center gap-2 rounded-full border border-border bg-surface px-4 py-2 text-sm text-text">
                                    @if ($skill->icon)
                                        <i class="{{ $skill->icon }} text-accent-content" aria-hidden="true"></i>
                                    @endif
                                    {{ $skill->getTranslation('name', $locale) }}
                                </span>
                            @endforeach
                        </div>
                    </x-molecules.card>
                @endforeach
            </div>
        </div>
    </section>
@endif
