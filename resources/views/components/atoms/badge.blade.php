@props(['color' => 'default'])

@php
    $classes = match ($color) {
        'accent' => 'bg-accent/10 text-accent border-accent/30',
        'success' => 'bg-green/10 text-green border-green/30',
        'warning' => 'bg-amber-500/10 text-amber-600 border-amber-500/20 dark:text-amber-400',
        default => 'bg-surface-muted text-text-muted border-border',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-medium tracking-wide '.$classes]) }}>
    {{ $slot }}
</span>
