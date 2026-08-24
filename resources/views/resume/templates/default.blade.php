{{--
    Resume template: default
    Included by resume/print.blade.php — has access to $data (ResumeData DTO).
    Inline CSS only; no Tailwind, no external dependencies.
--}}
@php
    $locale   = $data->locale;
    $profile  = $data->profile;
    $grouped  = $data->skills->groupBy('category');
@endphp

<style>
    /* ── Layout ── */
    .page        { max-width: 780px; margin: 0 auto; padding: 0 4px; }
    .header      { display: flex; justify-content: space-between; align-items: flex-start;
                   border-bottom: 2.5px solid #e8b84b; padding-bottom: 14px; margin-bottom: 22px; }
    .header-left { flex: 1; }
    .header-right { text-align: right; font-size: 9.5pt; color: #555; flex-shrink: 0; margin-left: 16px; }

    /* ── Typography ── */
    h1 { font-size: 22pt; font-weight: 700; color: #1a1a2e; line-height: 1.15; }
    h2 { font-size: 10.5pt; font-weight: 700; text-transform: uppercase; letter-spacing: .08em;
         color: #b8860b; border-bottom: 1px solid #e8b84b; padding-bottom: 3px;
         margin-bottom: 10px; margin-top: 20px; }
    h3 { font-size: 10.5pt; font-weight: 600; color: #1a1a2e; }

    .headline { font-size: 12pt; color: #444; margin-top: 3px; }
    .meta     { font-size: 9pt; color: #666; margin-top: 4px; display: flex; flex-wrap: wrap; gap: 10px; }
    .meta span::before { content: '▸ '; font-size: 7pt; color: #b8860b; }

    /* ── Sections ── */
    section { margin-bottom: 4px; }

    /* ── Experience / Education ── */
    .entry           { margin-bottom: 12px; }
    .entry-header    { display: flex; justify-content: space-between; align-items: baseline; }
    .entry-sub       { font-size: 9.5pt; color: #555; margin-top: 1px; }
    .entry-desc      { font-size: 9.5pt; color: #444; margin-top: 5px; line-height: 1.55; }
    .date            { font-size: 9pt; color: #777; white-space: nowrap; margin-left: 10px; flex-shrink: 0; }

    /* ── Skills ── */
    .skill-group     { margin-bottom: 6px; display: flex; flex-wrap: wrap; align-items: baseline; gap: 0; }
    .skill-category  { font-size: 9pt; font-weight: 600; color: #555; min-width: 130px; }
    .skill-tags      { display: flex; flex-wrap: wrap; gap: 4px; }
    .skill-tag       { font-size: 8.5pt; border: 1px solid #d0a83c; color: #7a5c00;
                       border-radius: 3px; padding: 1px 6px; }

    /* ── Certifications / Languages ── */
    .cert-row    { display: flex; justify-content: space-between; margin-bottom: 7px; }
    .cert-left   h3 { font-size: 10pt; }
    .cert-left   p  { font-size: 9pt; color: #666; margin-top: 1px; }
    .cert-right  { text-align: right; font-size: 9pt; color: #777; flex-shrink: 0; margin-left: 10px; }

    .lang-list   { display: flex; flex-wrap: wrap; gap: 14px; }
    .lang-item   { font-size: 9.5pt; }
    .lang-level  { font-size: 8.5pt; color: #777; margin-left: 4px; }
</style>

<div class="page">

    {{-- ── Header ── --}}
    <header class="header">
        <div class="header-left">
            <h1>{{ $profile?->full_name ?? config('app.name') }}</h1>
            @if ($profile)
                <p class="headline">{{ $profile->getTranslation('headline', $locale) }}</p>
                <div class="meta">
                    @if ($profile->email)    <span>{{ $profile->email }}</span> @endif
                    @if ($profile->phone)    <span>{{ $profile->phone }}</span> @endif
                    @if ($profile->location) <span>{{ $profile->location }}</span> @endif
                </div>
            @endif
        </div>
        @if ($profile && !empty($profile->social_links))
            <div class="header-right">
                @foreach ($profile->social_links as $link)
                    <div>{{ $link['label'] ?? '' }}: {{ $link['url'] ?? '' }}</div>
                @endforeach
            </div>
        @endif
    </header>

    {{-- ── Bio ── --}}
    @if ($profile?->getTranslation('bio', $locale))
        <section>
            <h2>{{ __('profile') }}</h2>
            <p class="entry-desc">{{ $profile->getTranslation('bio', $locale) }}</p>
        </section>
    @endif

    {{-- ── Experience ── --}}
    @if ($data->experiences->isNotEmpty())
        <section>
            <h2>{{ __('resume.experience') }}</h2>
            @foreach ($data->experiences as $exp)
                <div class="entry">
                    <div class="entry-header">
                        <h3>{{ $exp->getTranslation('title', $locale) }}</h3>
                        <span class="date">
                            {{ $exp->start_date->format('M Y') }} —
                            {{ $exp->end_date ? $exp->end_date->format('M Y') : __('resume.present') }}
                        </span>
                    </div>
                    <p class="entry-sub">
                        {{ $exp->company }}
                        @if ($exp->location) · {{ $exp->location }} @endif
                    </p>
                    @if ($exp->getTranslation('description', $locale))
                        <p class="entry-desc">{{ $exp->getTranslation('description', $locale) }}</p>
                    @endif
                </div>
            @endforeach
        </section>
    @endif

    {{-- ── Education ── --}}
    @if ($data->education->isNotEmpty())
        <section>
            <h2>{{ __('resume.education') }}</h2>
            @foreach ($data->education as $edu)
                <div class="entry">
                    <div class="entry-header">
                        <h3>
                            {{ $edu->getTranslation('degree', $locale) }}
                            @if ($edu->getTranslation('field', $locale))
                                <span style="font-weight:400"> · {{ $edu->getTranslation('field', $locale) }}</span>
                            @endif
                        </h3>
                        <span class="date">
                            {{ $edu->start_date->format('Y') }} —
                            {{ $edu->end_date ? $edu->end_date->format('Y') : __('resume.present') }}
                        </span>
                    </div>
                    <p class="entry-sub">{{ $edu->institution }}</p>
                    @if ($edu->getTranslation('description', $locale))
                        <p class="entry-desc">{{ $edu->getTranslation('description', $locale) }}</p>
                    @endif
                </div>
            @endforeach
        </section>
    @endif

    {{-- ── Skills ── --}}
    @if ($grouped->isNotEmpty())
        <section>
            <h2>{{ __('resume.skills') }}</h2>
            @foreach ($grouped as $category => $categorySkills)
                <div class="skill-group">
                    <span class="skill-category">{{ $category }}</span>
                    <div class="skill-tags">
                        @foreach ($categorySkills as $skill)
                            <span class="skill-tag">{{ $skill->getTranslation('name', $locale) }}</span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </section>
    @endif

    {{-- ── Certifications ── --}}
    @if ($data->certifications->isNotEmpty())
        <section>
            <h2>{{ __('resume.certifications') }}</h2>
            @foreach ($data->certifications as $cert)
                <div class="cert-row">
                    <div class="cert-left">
                        <h3>{{ $cert->getTranslation('title', $locale) }}</h3>
                        <p>{{ $cert->issuer }}</p>
                    </div>
                    <div class="cert-right">
                        @if ($cert->issued_at) {{ $cert->issued_at->format('M Y') }} @endif
                        @if ($cert->credential_url) <div>{{ $cert->credential_url }}</div> @endif
                    </div>
                </div>
            @endforeach
        </section>
    @endif

    {{-- ── Languages ── --}}
    @if ($data->languages->isNotEmpty())
        <section>
            <h2>{{ __('resume.languages') }}</h2>
            <div class="lang-list">
                @foreach ($data->languages as $lang)
                    <div class="lang-item">
                        <strong>{{ $lang->getTranslation('name', $locale) }}</strong>
                        <span class="lang-level">{{ __('resume.level_'.$lang->level) }}</span>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

</div>
