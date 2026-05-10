<x-layouts.public :title="__('Home')">
    <section class="space-y-6">
        <p class="text-sm font-medium uppercase tracking-wide text-accent">
            {{ __('Phase 0 placeholder') }}
        </p>
        <h1 class="font-display text-4xl font-semibold sm:text-5xl">
            {{ __('Bilingual portfolio') }}
        </h1>
        <p class="max-w-2xl text-lg text-text-muted">
            {{ __('This is a placeholder home page. The real content lands in Phase 2.') }}
        </p>
        <ul class="grid gap-2 text-sm text-text-muted">
            <li>{{ __('Locale') }}: <code class="font-mono">{{ app()->getLocale() }}</code></li>
            <li>{{ __('Brand token') }}: <code class="font-mono">--color-brand-600</code></li>
            <li>{{ __('Admin panel') }}: <a class="text-accent hover:underline" href="{{ url('/admin') }}">/admin</a></li>
        </ul>
    </section>
</x-layouts.public>
