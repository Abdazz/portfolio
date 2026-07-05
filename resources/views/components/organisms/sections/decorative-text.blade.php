@props(['text' => null])

@php($text = $text ?: config('app.name'))

<section class="overflow-hidden py-16 md:py-24" aria-hidden="true">
    <x-molecules.marquee speed="35s">
        <span class="text-6xl font-semibold tracking-tight text-transparent md:text-8xl lg:text-9xl [-webkit-text-stroke:1px_var(--color-border)]">
            {{ $text }}
        </span>
        <span class="text-6xl font-semibold tracking-tight gradient-text md:text-8xl lg:text-9xl">
            {{ $text }}
        </span>
    </x-molecules.marquee>
</section>
