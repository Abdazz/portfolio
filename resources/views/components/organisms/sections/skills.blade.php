@props(['skills'])

@php($locale = app()->getLocale())

@if ($skills->isNotEmpty())
    <section id="skills" class="py-[60px] md:py-20 lg:py-[100px]">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <x-molecules.section-heading :title="__('home.skills_title')">
                {{ __('home.skills_lead') }}
            </x-molecules.section-heading>

            <div class="flex flex-wrap items-stretch justify-center gap-5">
                @foreach ($skills as $skill)
                    <div class="group w-full max-w-[180px]" data-reveal>
                        <div class="mb-[15px] flex min-h-[120px] flex-col items-center justify-center rounded-[25px] border border-transparent bg-surface-muted px-[15px] py-[25px] transition-all duration-500 group-hover:border-accent group-hover:bg-accent-deep md:py-[30px]">
                            @if ($skill->icon)
                                <i class="{{ $skill->icon }} mb-3 text-4xl text-text-muted opacity-60 transition-all duration-500 group-hover:scale-110 group-hover:text-accent group-hover:opacity-100" aria-hidden="true"></i>
                            @endif
                            <div class="text-center text-xl font-extrabold text-text-muted transition-colors duration-300 group-hover:text-accent">
                                {{ $skill->getTranslation('name', $locale) }}
                            </div>
                        </div>
                        @if (filled($skill->category))
                            <p class="text-center text-sm text-accent">{{ $skill->category }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
