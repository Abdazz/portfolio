@props(['href', 'external' => false])

@php
    $attrs = ['href' => $href, 'class' => 'font-medium text-accent-content underline decoration-accent/30 decoration-1 underline-offset-4 transition-colors duration-300 hover:text-accent hover:decoration-accent'];
    if ($external) {
        $attrs['target'] = '_blank';
        $attrs['rel'] = 'noopener noreferrer';
    }
@endphp

<a {{ $attributes->merge($attrs) }}>{{ $slot }}</a>
