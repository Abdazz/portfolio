@props(['profile' => null])

<section class="py-20 md:py-28 lg:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-[2.5rem] gradient-primary px-8 py-16 text-center md:px-16 md:py-24" data-reveal>
            <div class="pointer-events-none absolute inset-0 -z-0 opacity-30 [background:radial-gradient(circle_at_30%_20%,rgba(255,255,255,0.35),transparent_45%)]" aria-hidden="true"></div>

            <div class="relative mx-auto flex max-w-2xl flex-col items-center gap-6">
                <span class="text-sm font-medium uppercase tracking-[0.2em] text-white/80">{{ __('home.contact_eyebrow') }}</span>
                <h2 class="text-3xl font-semibold leading-tight text-white md:text-5xl">{{ __('home.contact_title') }}</h2>
                <p class="text-lg leading-relaxed text-white/85">{{ __('home.contact_lead') }}</p>
                <a href="{{ route('contact') }}" class="mt-2">
                    <span class="group inline-flex items-center justify-center gap-2.5 rounded-full bg-white px-9 py-4 text-sm font-semibold text-accent-deep transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-black/20">
                        {{ __('home.contact_button') }}
                        <flux:icon name="arrow-up-right" class="size-4 transition-transform duration-300 group-hover:translate-x-0.5" />
                    </span>
                </a>
            </div>
        </div>
    </div>
</section>
