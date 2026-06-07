@php
    $locale = app()->getLocale();
@endphp

<x-layouts.public :title="__('resume.title')">

    <div class="space-y-16">

        {{-- Header --}}
        <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between animate-enter">
            <div class="space-y-4">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-accent">{{ __('resume.title') }}</p>
                <h1 class="font-display font-semibold leading-none text-text" style="font-size: clamp(2.5rem, 6vw, 5rem); font-optical-sizing: auto;">
                    {{ $profile?->full_name ?? config('app.name') }}
                </h1>
                @if ($profile)
                    <p class="text-base text-text-muted">
                        {{ $profile->getTranslation('headline', $locale) }}
                    </p>
                    <div class="flex flex-wrap items-center gap-x-5 gap-y-1.5 text-xs text-text-muted">
                        @if ($profile->email)
                            <span class="flex items-center gap-1.5">
                                <flux:icon name="envelope" class="size-3.5" />
                                {{ $profile->email }}
                            </span>
                        @endif
                        @if ($profile->location)
                            <span class="flex items-center gap-1.5">
                                <flux:icon name="map-pin" class="size-3.5" />
                                {{ $profile->location }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex shrink-0 flex-col items-start gap-2 sm:items-end">
                <x-atoms.button href="{{ route('resume.download') }}" icon="arrow-down-tray">
                    {{ __('resume.download') }}
                </x-atoms.button>
                <x-atoms.link href="{{ route('resume.json') }}" :external="false">
                    {{ __('resume.export_json') }}
                </x-atoms.link>
            </div>
        </div>

        <div class="border-t border-border/50"></div>

        {{-- Bio --}}
        @if ($profile?->getTranslation('bio', $locale))
            <div class="animate-enter animate-enter-delay-1">
                <x-organisms.resume-section :title="__('profile')" number="01">
                    <p class="max-w-2xl text-base text-text-muted leading-relaxed">
                        {{ $profile->getTranslation('bio', $locale) }}
                    </p>
                </x-organisms.resume-section>
            </div>
        @endif

        {{-- Experience --}}
        @if ($experiences->isNotEmpty())
            <div class="animate-enter animate-enter-delay-2">
                <x-organisms.resume-section :title="__('resume.experience')" number="02">
                    <x-organisms.experience-timeline :experiences="$experiences" />
                </x-organisms.resume-section>
            </div>
        @endif

        {{-- Education --}}
        @if ($education->isNotEmpty())
            <div class="animate-enter animate-enter-delay-3">
                <x-organisms.resume-section :title="__('resume.education')" number="03">
                    <div class="space-y-8">
                        @foreach ($education as $edu)
                            <div class="grid gap-2 sm:grid-cols-[140px_1fr]">
                                <span class="text-xs font-semibold uppercase tracking-widest text-accent pt-0.5">
                                    {{ $edu->start_date->format('Y') }}–{{ $edu->end_date?->format('Y') ?? __('resume.present') }}
                                </span>
                                <div class="space-y-1 border-l border-border pl-5 sm:pl-6">
                                    <div class="flex flex-wrap items-baseline gap-x-2">
                                        <h3 class="font-display font-semibold text-text" style="font-optical-sizing: auto;">
                                            {{ $edu->getTranslation('degree', $locale) }}
                                        </h3>
                                        @if ($edu->getTranslation('field', $locale))
                                            <span class="text-sm text-text-muted">{{ $edu->getTranslation('field', $locale) }}</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-text-muted">{{ $edu->institution }}</p>
                                    @if ($edu->getTranslation('description', $locale))
                                        <p class="mt-1.5 text-sm text-text-muted leading-relaxed">{{ $edu->getTranslation('description', $locale) }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-organisms.resume-section>
            </div>
        @endif

        {{-- Skills --}}
        @if ($skills->isNotEmpty())
            <div class="animate-enter animate-enter-delay-4">
                <x-organisms.resume-section :title="__('resume.skills')" number="04">
                    <div class="space-y-6">
                        @foreach ($skills as $category => $categorySkills)
                            <div class="grid gap-3 sm:grid-cols-[140px_1fr]">
                                <span class="text-xs font-semibold uppercase tracking-widest text-text-muted pt-0.5">{{ $category }}</span>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($categorySkills as $skill)
                                        <x-atoms.badge>{{ $skill->getTranslation('name', $locale) }}</x-atoms.badge>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-organisms.resume-section>
            </div>
        @endif

        {{-- Certifications --}}
        @if ($certifications->isNotEmpty())
            <div class="animate-enter animate-enter-delay-5">
                <x-organisms.resume-section :title="__('resume.certifications')" number="05">
                    <div class="space-y-6">
                        @foreach ($certifications as $cert)
                            <div class="grid gap-2 sm:grid-cols-[140px_1fr]">
                                <span class="text-xs font-semibold uppercase tracking-widest text-accent pt-0.5">
                                    {{ $cert->issued_at?->format('Y') }}
                                </span>
                                <div class="flex items-start justify-between gap-4 border-l border-border pl-5 sm:pl-6">
                                    <div>
                                        <p class="font-display font-semibold text-text" style="font-optical-sizing: auto;">{{ $cert->getTranslation('title', $locale) }}</p>
                                        <p class="text-sm text-text-muted">{{ $cert->issuer }}</p>
                                        @if ($cert->issued_at)
                                            <p class="text-xs text-text-muted mt-0.5">{{ $cert->issued_at->format('M Y') }}</p>
                                        @endif
                                    </div>
                                    @if ($cert->credential_url)
                                        <x-atoms.link href="{{ $cert->credential_url }}" :external="true" class="shrink-0 text-xs">
                                            {{ __('Verify') }}
                                        </x-atoms.link>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-organisms.resume-section>
            </div>
        @endif

        {{-- Languages --}}
        @if ($languages->isNotEmpty())
            <div class="animate-enter animate-enter-delay-6">
                <x-organisms.resume-section :title="__('resume.languages')" number="{{ str_pad(($certifications->isNotEmpty() ? 6 : 5), 2, '0', STR_PAD_LEFT) }}">
                    <div class="flex flex-wrap gap-4">
                        @foreach ($languages as $lang)
                            <div class="flex items-center gap-2.5">
                                <span class="font-display font-semibold text-text" style="font-optical-sizing: auto;">{{ $lang->getTranslation('name', $locale) }}</span>
                                <x-atoms.badge>{{ $lang->level }}</x-atoms.badge>
                            </div>
                        @endforeach
                    </div>
                </x-organisms.resume-section>
            </div>
        @endif

    </div>

</x-layouts.public>
