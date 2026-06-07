@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    $currentLocale = app()->getLocale();
    $locales = LaravelLocalization::getSupportedLocales();
@endphp

<div class="flex items-center gap-1" aria-label="{{ __('nav.switch_locale') }}">
    @foreach ($locales as $locale => $properties)
        @if ($locale !== $currentLocale)
            <a
                href="{{ LaravelLocalization::getLocalizedURL($locale, null, [], true) }}"
                hreflang="{{ $locale }}"
                class="px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-text-muted hover:text-accent transition-colors"
                rel="alternate"
            >
                {{ $locale }}
            </a>
        @else
            <span class="px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-accent cursor-default" aria-current="true">
                {{ $locale }}
            </span>
        @endif
    @endforeach
</div>
