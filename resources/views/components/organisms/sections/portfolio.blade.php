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

            <div class="mt-10 flex justify-center md:mt-[50px]" data-reveal>
                <a href="{{ route('projects.index') }}"
                   class="rounded-full gradient-secondary px-[35px] py-[17px] text-[15px] font-bold capitalize leading-none text-white transition-all duration-300 hover:[background-position:-100%_0]">
                    {{ __('home.featured_more') }}
                </a>
            </div>
        </div>
    </section>
@endif
