@props(['padding' => true])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-border bg-surface'.($padding ? ' p-6' : '')]) }}>
    {{ $slot }}
</div>
