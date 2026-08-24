<x-layouts.public :title="__('contact.title')">

    <div class="space-y-12">
        <div class="space-y-5 animate-enter">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-accent">{{ __('nav.contact') }}</p>
            <h1 class="font-display font-semibold leading-tight text-text" style="font-size: clamp(2.25rem, 5vw, 4rem); font-optical-sizing: auto;">
                {{ __('contact.title') }}
            </h1>
            <p class="max-w-xl text-base text-text-muted leading-relaxed">{{ __('contact.subtitle') }}</p>
        </div>

        <div class="border-t border-border/50 pt-10 animate-enter animate-enter-delay-2">
            <div class="max-w-xl">
                <livewire:contact-form />
            </div>
        </div>
    </div>

</x-layouts.public>
