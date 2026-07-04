@props(['href' => null, 'variant' => 'primary', 'size' => 'base', 'icon' => null, 'external' => false])

@php
    $base = 'group inline-flex items-center justify-center gap-2.5 rounded-full font-medium leading-none tracking-wide transition-all duration-300 disabled:opacity-60 disabled:pointer-events-none';

    $sizes = match ($size) {
        'sm' => 'px-6 py-3 text-xs',
        'lg' => 'px-10 py-5 text-base',
        default => 'px-8 py-4 text-sm',
    };

    $variants = match ($variant) {
        'primary' => 'gradient-primary text-white shadow-lg shadow-accent/20 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-accent/30',
        'outline' => 'border border-accent text-accent bg-transparent hover:bg-accent hover:text-white',
        'secondary' => 'border border-border bg-surface-muted text-text hover:border-accent hover:text-accent',
        'ghost' => 'text-text-muted hover:text-accent',
        default => '',
    };

    $classes = trim("{$base} {$sizes} {$variants}");
    unset($variant, $size);

    $extraAttrs = $external ? ['target' => '_blank', 'rel' => 'noopener noreferrer'] : [];
@endphp

@if (isset($href))
    <a {{ $attributes->merge(array_merge(['href' => $href, 'class' => $classes], $extraAttrs)) }}>
        {{ $slot }}
        @if ($icon)
            <flux:icon :name="$icon" class="size-4 transition-transform duration-300 group-hover:translate-x-0.5" />
        @endif
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $classes]) }}>
        {{ $slot }}
        @if ($icon)
            <flux:icon :name="$icon" class="size-4 transition-transform duration-300 group-hover:translate-x-0.5" />
        @endif
    </button>
@endif
