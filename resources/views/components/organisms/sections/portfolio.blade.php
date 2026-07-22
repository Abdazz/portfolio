@props(['projects', 'limit' => 6])

@php($items = $projects->take($limit))

@if ($items->isNotEmpty())
    <section id="portfolio" class="pt-[60px] pb-[30px] md:pt-20 md:pb-[60px] lg:pt-[100px] lg:pb-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <x-molecules.section-heading :title="__('home.portfolio_title')">
                {{ __('home.portfolio_lead') }}
            </x-molecules.section-heading>

            <div class="mt-10 grid gap-[30px] sm:grid-cols-2 md:mt-[50px]">
                @foreach ($items as $i => $project)
                    <x-molecules.project-card :project="$project" :index="$i" />
                @endforeach
            </div>
        </div>
    </section>
@endif
