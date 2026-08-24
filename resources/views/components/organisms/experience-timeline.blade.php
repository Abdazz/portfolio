@props(['experiences'])

@php
    $locale = app()->getLocale();
@endphp

<div class="space-y-10">
    @foreach ($experiences as $experience)
        @php
            $title = $experience->getTranslation('title', $locale);
            $description = $experience->getTranslation('description', $locale);
            $startYear = $experience->start_date->format('Y');
            $endYear = $experience->end_date?->format('Y') ?? __('resume.present');
        @endphp

        <div class="grid gap-3 sm:grid-cols-[140px_1fr]">
            <div class="flex flex-col gap-1 sm:pt-0.5">
                <span class="text-xs font-semibold uppercase tracking-widest text-accent">
                    {{ $startYear }}–{{ $endYear }}
                </span>
                @if ($experience->location)
                    <span class="text-xs text-text-muted">{{ $experience->location }}</span>
                @endif
            </div>

            <div class="space-y-1.5 border-l border-border pl-5 sm:pl-6">
                <div class="flex flex-wrap items-baseline gap-x-2">
                    <h3 class="font-display font-semibold text-text" style="font-optical-sizing: auto;">{{ $title }}</h3>
                    <span class="text-sm text-text-muted">{{ $experience->company }}</span>
                </div>

                @if ($description)
                    <p class="text-sm text-text-muted leading-relaxed">{{ $description }}</p>
                @endif
            </div>
        </div>
    @endforeach
</div>
