@props(['experiences', 'educations' => null])

@php
    $locale = app()->getLocale();

    $periodOf = function ($item) {
        $start = $item->start_date?->year;
        $end = $item->end_date?->year ?? __('home.period_present');

        return trim("{$start} - {$end}");
    };

    $cardClasses = 'group relative z-0 max-w-[536px] rounded-[20px] bg-surface px-[15px] py-5 transition-all duration-500 after:absolute after:top-0 after:left-0 after:-z-[1] after:h-full after:w-full after:rounded-[20px] after:opacity-0 after:transition-all after:duration-500 after:gradient-primary hover:after:opacity-100 md:px-[30px] dark:bg-surface-muted';
@endphp

@if ($experiences->isNotEmpty() || ($educations?->isNotEmpty() ?? false))
    <section id="resume" class="bg-surface-deep py-[60px] md:py-20 lg:py-[120px] dark:bg-surface-deep">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-[30px] gap-y-[50px] md:grid-cols-2">
                @if ($experiences->isNotEmpty())
                    <div>
                        <div class="mb-10 md:mb-[50px]" data-reveal>
                            <h2 class="gradient-text mb-[15px] text-3xl font-semibold md:text-[35px] lg:text-[40px] xl:text-[45px] xl:leading-[1.2]">
                                <i class="flaticon-recommendation mr-2" aria-hidden="true"></i> {{ __('home.my_experience') }}
                            </h2>
                        </div>

                        <div class="flex flex-col gap-[30px]">
                            @foreach ($experiences as $experience)
                                <div class="{{ $cardClasses }}" data-reveal>
                                    <div class="relative z-10 text-text transition-all duration-300 group-hover:text-white">
                                        <p class="mb-2 text-[15px] font-extrabold text-accent transition-all duration-300 group-hover:text-white lg:text-xl">
                                            {{ $periodOf($experience) }}
                                        </p>
                                        <h4 class="mb-2 text-lg uppercase md:text-xl lg:text-[23px] 2xl:text-[25px]">
                                            {{ $experience->getTranslation('title', $locale) }}
                                        </h4>
                                        <p class="text-text-muted transition-all duration-300 group-hover:text-white">
                                            {{ collect([$experience->company, $experience->location])->filter()->join(', ') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($educations?->isNotEmpty() ?? false)
                    <div>
                        <div class="mb-10 md:mb-[50px]" data-reveal>
                            <h2 class="gradient-text mb-[15px] text-3xl font-semibold md:text-[35px] lg:text-[40px] xl:text-[45px] xl:leading-[1.2]">
                                <i class="flaticon-graduation-cap mr-2" aria-hidden="true"></i> {{ __('home.my_education') }}
                            </h2>
                        </div>

                        <div class="flex flex-col gap-[30px]">
                            @foreach ($educations as $education)
                                <div class="{{ $cardClasses }}" data-reveal>
                                    <div class="relative z-10 text-text transition-all duration-300 group-hover:text-white">
                                        <p class="mb-2 text-[15px] font-extrabold text-accent transition-all duration-300 group-hover:text-white lg:text-xl">
                                            {{ $periodOf($education) }}
                                        </p>
                                        <h4 class="mb-2 text-lg uppercase md:text-xl lg:text-[23px] 2xl:text-[25px]">
                                            {{ $education->getTranslation('degree', $locale) }}
                                        </h4>
                                        <p class="text-text-muted transition-all duration-300 group-hover:text-white">
                                            {{ $education->institution }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
