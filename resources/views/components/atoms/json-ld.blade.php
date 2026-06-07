@props([
    'type'    => 'Person',   // 'Person' | 'CreativeWork'
    'profile' => null,
    'project' => null,
])

@php
    $locale = app()->getLocale();
    $siteName = config('app.name');
    $siteUrl  = url('/');
@endphp

@if ($type === 'Person' && $profile)
    @php
        $json = [
            '@context' => 'https://schema.org',
            '@type'    => 'Person',
            'name'     => $profile->full_name,
            'url'      => route('home'),
            'email'    => $profile->email,
            'jobTitle' => $profile->getTranslation('headline', $locale),
        ];

        if ($profile->social_links) {
            $json['sameAs'] = collect($profile->social_links)->pluck('url')->filter()->values()->toArray();
        }
    @endphp
    <script type="application/ld+json">{!! json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

@elseif ($type === 'CreativeWork' && $project)
    @php
        $title = $project->getTranslation('title', $locale);
        $summary = $project->getTranslation('summary', $locale);
        $links = $project->links ?? [];
        $json = [
            '@context'    => 'https://schema.org',
            '@type'       => 'CreativeWork',
            'name'        => $title,
            'description' => $summary,
            'url'         => url()->current(),
            'creator'     => [
                '@type' => 'Person',
                'name'  => $siteName,
            ],
        ];

        $firstLink = collect($links)->first();
        if ($firstLink) {
            $json['codeRepository'] = $firstLink['url'];
        }
    @endphp
    <script type="application/ld+json">{!! json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endif
