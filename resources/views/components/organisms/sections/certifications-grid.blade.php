@props(['certifications'])

@php($locale = app()->getLocale())

@if ($certifications->isNotEmpty())
    <section id="certifications" class="py-[60px] md:py-20 lg:py-[100px]">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <x-molecules.section-heading :title="__('home.certifications_title')" />

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($certifications as $cert)
                    @php($badge = $cert->getFirstMediaUrl('badge'))

                    <div class="group flex flex-col rounded-[25px] border border-transparent bg-surface-muted p-6 transition-all duration-500 hover:border-accent hover:bg-accent-deep" data-reveal>
                        <div class="mb-4 flex h-12 items-center">
                            @if ($badge)
                                <img src="{{ $badge }}" alt="" class="h-12 w-auto max-w-[96px] object-contain" loading="lazy" width="96" height="48">
                            @else
                                <i class="fa-solid fa-award text-3xl text-text-muted opacity-60 transition-colors duration-300 group-hover:text-accent group-hover:opacity-100" aria-hidden="true"></i>
                            @endif
                        </div>

                        <h3 class="mb-1 text-base font-bold text-text group-hover:text-white">
                            {{ $cert->getTranslation('title', $locale) }}
                        </h3>

                        <p class="mb-3 text-sm text-text-muted group-hover:text-white/70">
                            {{ $cert->issuer }}
                            @if ($cert->issued_at)
                                &middot; {{ $cert->issued_at->translatedFormat('M Y') }}
                            @endif
                        </p>

                        @if ($cert->credential_url)
                            <a href="{{ $cert->credential_url }}" target="_blank" rel="noopener noreferrer"
                               class="mt-auto inline-flex items-center gap-1 text-sm font-medium text-accent transition-colors group-hover:text-white">
                                {{ __('home.certifications_verify') }}
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs" aria-hidden="true"></i>
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
