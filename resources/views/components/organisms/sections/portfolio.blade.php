@props(['projects', 'limit' => 6])

@php($items = $projects->take($limit))

@if ($items->isNotEmpty())
    <section class="py-20 md:py-28 lg:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between" data-reveal>
                <x-molecules.section-heading :eyebrow="__('home.portfolio_eyebrow')" :title="__('home.portfolio_title')" />
                <x-atoms.button href="{{ route('projects.index') }}" variant="outline" size="sm" icon="arrow-up-right" class="shrink-0">{{ __('home.featured_more') }}</x-atoms.button>
            </div>

            <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($items as $i => $project)
                    <x-molecules.project-card :project="$project" :index="$i" />
                @endforeach
            </div>
        </div>
    </section>
@endif
