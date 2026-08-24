@props(['href', 'external' => false])

@php
    $attrs = ['href' => $href, 'class' => 'text-accent hover:text-accent-content underline underline-offset-4 decoration-accent/40 hover:decoration-accent-content transition-colors'];
    if ($external) {
        $attrs['target'] = '_blank';
        $attrs['rel'] = 'noopener noreferrer';
    }
@endphp

<a {{ $attributes->merge($attrs) }}>{{ $slot }}</a>
