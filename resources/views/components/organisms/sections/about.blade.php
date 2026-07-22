@props(['profile', 'stats' => []])

@php
    $locale = app()->getLocale();
    $bio = $profile?->getTranslation('bio', $locale);

    $tiles = [
        ['value' => $stats['projects'] ?? 0, 'label' => __('home.stat_projects'), 'suffix' => '+'],
        ['value' => $stats['years'] ?? 0, 'label' => __('home.stat_years'), 'suffix' => '+'],
        ['value' => $stats['certifications'] ?? 0, 'label' => __('home.stat_certifications'), 'suffix' => ''],
        ['value' => $stats['languages'] ?? 0, 'label' => __('home.stat_languages'), 'suffix' => ''],
    ];
@endphp

<section class="py-20 md:py-28 lg:py-32">
    <div class="mx-auto grid max-w-7xl gap-12 px-6 lg:grid-cols-2 lg:gap-20 lg:px-8">
        <div class="flex flex-col gap-6">
            <x-molecules.section-heading :title="__('home.about_title')" align="left" />
            @if ($bio)
                <p class="text-lg leading-relaxed text-text-muted" data-reveal>{{ $bio }}</p>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-x-8 gap-y-12 self-center" data-reveal>
            @foreach ($tiles as $tile)
                <x-molecules.stat-counter :value="$tile['value']" :label="$tile['label']" :suffix="$tile['suffix']" />
            @endforeach
        </div>
    </div>
</section>
