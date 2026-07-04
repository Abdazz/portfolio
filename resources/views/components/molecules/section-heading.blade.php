@props(['eyebrow' => null, 'title', 'align' => 'left'])

<div @class([
    'flex flex-col gap-4',
    'items-center text-center' => $align === 'center',
]) data-reveal>
    @if ($eyebrow)
        <span class="inline-flex items-center gap-2.5 text-sm font-medium uppercase tracking-[0.2em] text-accent-content">
            <span class="h-px w-8 bg-accent/50" aria-hidden="true"></span>
            {{ $eyebrow }}
        </span>
    @endif

    <h2 class="gradient-text max-w-3xl text-3xl font-medium leading-[1.15] md:text-4xl lg:text-5xl">
        {{ $title }}
    </h2>

    @if (! $slot->isEmpty())
        <p class="max-w-2xl text-base leading-relaxed text-text-muted">{{ $slot }}</p>
    @endif
</div>
