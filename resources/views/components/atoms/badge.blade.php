@props(['color' => 'default'])

@php
    $classes = match($color) {
        'accent'   => 'bg-accent/10 text-accent border-accent/20',
        'success'  => 'bg-green-500/10 text-green-500 border-green-500/20 dark:text-green-400',
        'warning'  => 'bg-amber-500/10 text-amber-600 border-amber-500/20 dark:text-amber-400',
        default    => 'bg-surface-muted text-text-muted border-border',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center border px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider '.$classes]) }}>
    {{ $slot }}
</span>
