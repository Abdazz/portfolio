@props(['eyebrow' => null, 'title', 'align' => 'center'])

{{-- Gerold-style section heading: gradient title + optional lead (centered by default) --}}
<div @class([
    'flex flex-col',
    'mb-10 items-center text-center md:mb-[50px]' => $align === 'center',
]) data-reveal>
    <h2 class="gradient-text mb-[15px] text-3xl font-semibold md:text-[35px] lg:text-[40px] xl:text-[45px] xl:leading-[1.2]">
        {{ $title }}
    </h2>

    @if (! $slot->isEmpty())
        <p class="max-w-[700px] text-text">{{ $slot }}</p>
    @endif
</div>
