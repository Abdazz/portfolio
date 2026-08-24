@props(['speed' => '30s'])

{{-- Seamless horizontal scroller: the track holds two copies of the slot so the
     -50% translate loops without a visible seam. Pauses under reduced-motion. --}}
<div {{ $attributes->merge(['class' => 'group relative w-full overflow-hidden']) }}>
    <div
        class="animate-marquee flex w-max shrink-0 items-center gap-12 pr-12 group-hover:[animation-play-state:paused]"
        style="--marquee-speed: {{ $speed }}"
    >
        <div class="flex items-center gap-12 pr-12" aria-hidden="false">{{ $slot }}</div>
        <div class="flex items-center gap-12 pr-12" aria-hidden="true">{{ $slot }}</div>
    </div>
</div>
