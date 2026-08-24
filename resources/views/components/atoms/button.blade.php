@props(['href', 'variant' => 'primary', 'size' => 'base', 'icon' => null, 'external' => false])

@php
    $classes = match($variant) {
        'primary'  => 'inline-flex items-center gap-2 px-5 py-2.5 text-xs font-semibold uppercase tracking-widest bg-accent text-accent-foreground hover:bg-accent-content transition-colors',
        'secondary' => 'inline-flex items-center gap-2 px-5 py-2.5 text-xs font-semibold uppercase tracking-widest border border-border text-text hover:bg-surface-muted hover:border-accent/40 transition-colors',
        'ghost'    => 'inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-text-muted hover:text-text transition-colors',
        default    => '',
    };
    unset($variant);

    $extraAttrs = [];
    if ($external) {
        $extraAttrs['target'] = '_blank';
        $extraAttrs['rel'] = 'noopener noreferrer';
    }
@endphp

@if (isset($href))
    <a {{ $attributes->merge(array_merge(['href' => $href, 'class' => $classes], $extraAttrs)) }}>
        @if ($icon)
            <flux:icon :name="$icon" class="size-3.5" />
        @endif
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $classes]) }}>
        @if ($icon)
            <flux:icon :name="$icon" class="size-3.5" />
        @endif
        {{ $slot }}
    </button>
@endif
