@props(['title', 'icon' => null, 'number' => null])

<section {{ $attributes->merge(['class' => 'space-y-8']) }}>
    <div class="flex items-baseline gap-4 border-b border-border pb-4">
        @if ($number)
            <span class="font-display text-xs font-semibold tabular-nums text-accent" style="font-optical-sizing: auto;">{{ $number }}</span>
            <span class="text-border/60">—</span>
        @elseif ($icon)
            <flux:icon :name="$icon" class="size-4 text-accent shrink-0" />
        @endif
        <h2 class="font-display text-xl font-semibold uppercase tracking-widest text-text" style="font-optical-sizing: auto;">{{ $title }}</h2>
    </div>

    {{ $slot }}
</section>
