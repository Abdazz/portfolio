@props(['certifications'])

@php($locale = app()->getLocale())

@if ($certifications->isNotEmpty())
    <section class="border-y border-border/60 py-14 md:py-20" aria-label="{{ __('home.certifications_title') }}">
        <p class="mx-auto mb-10 max-w-7xl px-6 text-center text-sm font-medium uppercase tracking-[0.2em] text-text-muted lg:px-8">
            {{ __('home.certifications_title') }}
        </p>

        <x-molecules.marquee speed="40s">
            @foreach ($certifications as $cert)
                <span class="inline-flex shrink-0 items-center gap-3 rounded-full border border-border bg-surface-muted px-6 py-3">
                    <span class="h-1.5 w-1.5 rounded-full bg-accent" aria-hidden="true"></span>
                    <span class="text-base font-medium text-text">{{ $cert->getTranslation('title', $locale) }}</span>
                    @if ($cert->issuer)
                        <span class="text-sm text-text-muted">· {{ $cert->issuer }}</span>
                    @endif
                </span>
            @endforeach
        </x-molecules.marquee>
    </section>
@endif
