@php
    $locale = app()->getLocale();
@endphp

<x-layouts.public :title="__('contact.title')">

    <section class="py-20 md:py-28 lg:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-[1fr_1.2fr] lg:gap-20">

                {{-- Intro + direct details --}}
                <div class="flex flex-col gap-6" data-reveal>
                    <span class="inline-flex items-center gap-2.5 text-sm font-medium uppercase tracking-[0.2em] text-accent-content">
                        <span class="h-px w-8 bg-accent/50" aria-hidden="true"></span>
                        {{ __('nav.contact') }}
                    </span>
                    <h1 class="gradient-text max-w-md text-4xl font-semibold leading-[1.05] md:text-5xl lg:text-6xl">
                        {{ __('contact.title') }}
                    </h1>
                    <p class="max-w-md text-base leading-relaxed text-text-muted">{{ __('contact.subtitle') }}</p>

                    @if ($profile)
                        <dl class="mt-4 flex flex-col gap-5">
                            @if ($profile->email)
                                <div class="flex items-start gap-4">
                                    <span class="flex size-11 shrink-0 items-center justify-center rounded-full border border-border bg-surface-muted text-accent-content">
                                        <flux:icon name="envelope" variant="outline" class="size-5" />
                                    </span>
                                    <div class="flex flex-col">
                                        <dt class="text-xs font-medium uppercase tracking-widest text-text-muted">{{ __('contact.email') }}</dt>
                                        <dd>
                                            <a href="mailto:{{ $profile->email }}" class="text-sm font-medium text-text transition-colors hover:text-accent-content">{{ $profile->email }}</a>
                                        </dd>
                                    </div>
                                </div>
                            @endif

                            @if ($profile->phone)
                                <div class="flex items-start gap-4">
                                    <span class="flex size-11 shrink-0 items-center justify-center rounded-full border border-border bg-surface-muted text-accent-content">
                                        <flux:icon name="phone" variant="outline" class="size-5" />
                                    </span>
                                    <div class="flex flex-col">
                                        <dt class="text-xs font-medium uppercase tracking-widest text-text-muted">{{ __('contact.phone') }}</dt>
                                        <dd>
                                            <a href="tel:{{ $profile->phone }}" class="text-sm font-medium text-text transition-colors hover:text-accent-content">{{ $profile->phone }}</a>
                                        </dd>
                                    </div>
                                </div>
                            @endif

                            @if ($profile->location)
                                <div class="flex items-start gap-4">
                                    <span class="flex size-11 shrink-0 items-center justify-center rounded-full border border-border bg-surface-muted text-accent-content">
                                        <flux:icon name="map-pin" variant="outline" class="size-5" />
                                    </span>
                                    <div class="flex flex-col">
                                        <dt class="text-xs font-medium uppercase tracking-widest text-text-muted">{{ __('contact.location') }}</dt>
                                        <dd class="text-sm font-medium text-text">{{ $profile->location }}</dd>
                                    </div>
                                </div>
                            @endif
                        </dl>
                    @endif
                </div>

                {{-- Form card --}}
                <div class="rounded-3xl border border-border bg-surface-muted/50 p-6 sm:p-8 lg:p-10" data-reveal>
                    <livewire:contact-form />
                </div>

            </div>
        </div>
    </section>

</x-layouts.public>
