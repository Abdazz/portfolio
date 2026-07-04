@props(['padding' => true])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-border bg-surface-muted transition-colors duration-300'.($padding ? ' p-6 md:p-8' : '')]) }}>
    {{ $slot }}
</div>
