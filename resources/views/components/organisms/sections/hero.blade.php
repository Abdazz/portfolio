@props(['profile', 'stats' => null])

@php
    $locale = app()->getLocale();
    $headline = $profile?->getTranslation('headline', $locale) ?: __('home.hero_title');
    $bio = $profile?->getTranslation('bio', $locale) ?: __('home.hero_lead');
    $avatar = $profile?->getFirstMediaUrl('avatar') ?: null;
    $greetingName = $profile?->full_name ?: config('app.name');

    $iconFor = fn (string $platform): string => match (strtolower($platform)) {
        'linkedin' => 'fa-brands fa-linkedin-in',
        'github' => 'fa-brands fa-github',
        'twitter', 'x' => 'fa-brands fa-x-twitter',
        'facebook' => 'fa-brands fa-facebook-f',
        'instagram' => 'fa-brands fa-instagram',
        'youtube' => 'fa-brands fa-youtube',
        'dribbble' => 'fa-brands fa-dribbble',
        'behance' => 'fa-brands fa-behance',
        'gitlab' => 'fa-brands fa-gitlab',
        'medium' => 'fa-brands fa-medium',
        'dev', 'devto' => 'fa-brands fa-dev',
        'stackoverflow', 'stack-overflow' => 'fa-brands fa-stack-overflow',
        'whatsapp' => 'fa-brands fa-whatsapp',
        'telegram' => 'fa-brands fa-telegram',
        default => 'fa-solid fa-globe',
    };

    $socials = collect($profile?->social_links ?? [])
        ->map(fn ($value, $key) => is_array($value)
            ? ['label' => $value['label'] ?? ucfirst((string) $key), 'url' => $value['url'] ?? null, 'icon' => $iconFor($value['label'] ?? (string) $key)]
            : ['label' => ucfirst((string) $key), 'url' => $value, 'icon' => $iconFor((string) $key)])
        ->filter(fn ($s) => filled($s['url']))
        ->values();

    $socialCircleClasses = 'text-accent hover:text-white border border-accent w-[35px] h-[35px] rounded-full flex items-center justify-center overflow-hidden relative z-0 after:absolute after:top-1/2 after:left-1/2 after:-translate-x-1/2 after:-translate-y-1/2 after:w-full after:h-full after:scale-0 after:bg-accent hover:after:scale-105 after:transition-all after:duration-300 after:-z-[1] after:rounded-full transition-colors duration-300';

    $funfacts = collect([
        ['value' => $stats['years'] ?? null, 'label' => __('home.stat_years')],
        ['value' => $stats['projects'] ?? null, 'label' => __('home.stat_projects')],
        ['value' => $stats['certifications'] ?? null, 'label' => __('home.stat_certifications')],
        ['value' => $stats['languages'] ?? null, 'label' => __('home.stat_languages')],
    ])->filter(fn ($f) => filled($f['value']))->values();
@endphp

<section class="relative overflow-hidden pt-[130px] pb-10 md:pb-[30px] lg:pt-40 lg:pb-[50px] xl:pt-[200px] after:absolute after:top-0 after:right-0 after:-z-[1] after:-mt-[5%] after:-mr-[5%] after:h-[308px] after:w-[322px] after:rounded-full after:gradient-circle after:blur-[150px]">
    {{-- "HI" stroke-text watermark --}}
    <div class="intro-text" aria-hidden="true">
        <svg viewBox="0 0 1320 300" class="overflow-hidden">
            <text x="50%" y="50%" text-anchor="middle">{{ __('home.hero_watermark') }}</text>
        </svg>
    </div>

    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="gap-[30px] md:grid md:grid-cols-2 md:items-center">
            <div>
                <h4 class="mb-1.5 text-[22px] font-bold text-accent-deep md:text-[25px] lg:text-4xl xl:mb-[10px] dark:text-text" data-reveal>
                    {{ __('home.hero_greeting', ['name' => $greetingName]) }}
                </h4>

                <h1 class="gradient-text mb-[15px] text-[35px] font-semibold md:text-[38px] lg:text-[50px] xl:text-6xl xl:leading-[1.2] 2xl:text-[65px]" data-reveal>
                    {{ $headline }}
                </h1>

                @if ($avatar)
                    <div class="my-[30px] flex items-center justify-center md:hidden">
                        <img src="{{ $avatar }}" alt="{{ $greetingName }}"
                             class="max-w-[80%] rotate-[4.29deg] rounded-[38px] border-2 border-accent-deep transition-all duration-300 hover:rotate-0 hover:border-accent"
                             width="480" height="560">
                    </div>
                @endif

                <p class="max-w-[540px] text-xl leading-normal text-text" data-reveal>{{ $bio }}</p>

                <div class="mt-5 flex flex-wrap items-center gap-[30px] md:mt-[30px] lg:mt-[50px] lg:flex-nowrap lg:gap-[25px]" data-reveal>
                    <div>
                        <a href="{{ route('resume.download') }}"
                           class="whitespace-nowrap rounded-full border border-accent bg-transparent px-[35px] py-[17px] text-[15px] font-medium capitalize leading-none tracking-[1px] text-accent transition-colors duration-300 hover:bg-accent hover:text-white">
                            {{ __('home.hero_download_cv') }}
                            <i class="flaticon-download ml-0.5 text-[17px]" aria-hidden="true"></i>
                        </a>
                    </div>

                    @if ($socials->isNotEmpty())
                        <ul class="flex gap-x-5">
                            @foreach ($socials as $social)
                                <li>
                                    <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer"
                                       aria-label="{{ $social['label'] }}"
                                       class="{{ $socialCircleClasses }}">
                                        <i class="{{ $social['icon'] }}" aria-hidden="true"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            @if ($avatar)
                <div class="relative hidden md:flex md:items-center md:justify-center after:absolute after:bottom-0 after:left-0 after:-z-[1] after:h-[220px] after:w-[220px] after:rounded-full after:gradient-circle after:blur-[150px]" data-reveal>
                    <img src="{{ $avatar }}" alt="{{ $greetingName }}"
                         class="rotate-[4.29deg] rounded-[38px] border-2 border-accent-deep transition-all duration-300 hover:rotate-0 hover:border-accent"
                         width="480" height="560">
                </div>
            @endif
        </div>
    </div>

    {{-- funfact stats --}}
    @if ($funfacts->isNotEmpty())
        <div class="mt-[60px] xl:mt-[70px]">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-2 gap-x-6 gap-y-[30px] text-accent lg:grid-cols-4 dark:text-text">
                    @foreach ($funfacts as $fact)
                        <div class="flex flex-col flex-wrap items-center justify-center gap-[15px] sm:flex-row sm:flex-nowrap lg:justify-start" data-reveal>
                            <div class="text-[45px] font-medium tracking-[0.04em] md:text-[55px] xl:text-[64px]">
                                {{ $fact['value'] }}
                            </div>
                            <div class="max-w-[120px] leading-snug">{{ $fact['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</section>
