{{--
    Resume template: minimal
    Included by resume/print.blade.php — has access to $data (ResumeData DTO).
    Clean, typography-focused single-column layout.
--}}
@php
    $locale  = $data->locale;
    $profile = $data->profile;
    $grouped = $data->skills->groupBy('category');
@endphp

<style>
    .m-page { max-width: 680px; margin: 0 auto; padding: 0 4px; }

    .m-name  { font-size: 20pt; font-weight: 700; color: #111; }
    .m-sub   { font-size: 10.5pt; color: #555; margin-top: 4px; }
    .m-meta  { font-size: 9pt; color: #777; margin-top: 6px; }
    .m-divider { border: none; border-top: 1px solid #ddd; margin: 16px 0 12px; }

    h2.m-h2 { font-size: 10pt; font-weight: 700; text-transform: uppercase;
              letter-spacing: .1em; color: #333; margin: 18px 0 8px; }

    .m-entry { margin-bottom: 10px; }
    .m-row   { display: flex; justify-content: space-between; }
    .m-title { font-size: 10pt; font-weight: 600; }
    .m-date  { font-size: 9pt; color: #888; }
    .m-org   { font-size: 9.5pt; color: #666; margin-top: 1px; }
    .m-desc  { font-size: 9.5pt; color: #555; margin-top: 4px; line-height: 1.5; }

    .m-tags  { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 8px; }
    .m-tag   { font-size: 8.5pt; background: #f3f3f3; border-radius: 3px; padding: 2px 7px; color: #333; }
</style>

<div class="m-page">
    <div class="m-name">{{ $profile?->full_name ?? config('app.name') }}</div>
    @if ($profile)
        <div class="m-sub">{{ $profile->getTranslation('headline', $locale) }}</div>
        <div class="m-meta">
            {{ implode(' · ', array_filter([$profile->email, $profile->phone, $profile->location])) }}
        </div>
    @endif

    @if ($profile?->getTranslation('bio', $locale))
        <hr class="m-divider">
        <p class="m-desc">{{ $profile->getTranslation('bio', $locale) }}</p>
    @endif

    @if ($data->experiences->isNotEmpty())
        <h2 class="m-h2">{{ __('resume.experience') }}</h2>
        @foreach ($data->experiences as $exp)
            <div class="m-entry">
                <div class="m-row">
                    <span class="m-title">{{ $exp->getTranslation('title', $locale) }}</span>
                    <span class="m-date">
                        {{ $exp->start_date->format('M Y') }} —
                        {{ $exp->end_date ? $exp->end_date->format('M Y') : __('resume.present') }}
                    </span>
                </div>
                <div class="m-org">{{ $exp->company }}@if ($exp->location) · {{ $exp->location }} @endif</div>
                @if ($exp->getTranslation('description', $locale))
                    <p class="m-desc">{{ $exp->getTranslation('description', $locale) }}</p>
                @endif
            </div>
        @endforeach
    @endif

    @if ($data->education->isNotEmpty())
        <h2 class="m-h2">{{ __('resume.education') }}</h2>
        @foreach ($data->education as $edu)
            <div class="m-entry">
                <div class="m-row">
                    <span class="m-title">{{ $edu->getTranslation('degree', $locale) }}</span>
                    <span class="m-date">{{ $edu->start_date->format('Y') }} — {{ $edu->end_date?->format('Y') ?? __('resume.present') }}</span>
                </div>
                <div class="m-org">{{ $edu->institution }}</div>
            </div>
        @endforeach
    @endif

    @if ($grouped->isNotEmpty())
        <h2 class="m-h2">{{ __('resume.skills') }}</h2>
        @foreach ($grouped as $category => $categorySkills)
            <div class="m-tags">
                @foreach ($categorySkills as $skill)
                    <span class="m-tag">{{ $skill->getTranslation('name', $locale) }}</span>
                @endforeach
            </div>
        @endforeach
    @endif

    @if ($data->languages->isNotEmpty())
        <h2 class="m-h2">{{ __('resume.languages') }}</h2>
        <div class="m-tags">
            @foreach ($data->languages as $lang)
                <span class="m-tag">{{ $lang->getTranslation('name', $locale) }} — {{ __('resume.level_'.$lang->level) }}</span>
            @endforeach
        </div>
    @endif
</div>
