@props(['project', 'index' => 0])

@php
    $locale = app()->getLocale();
    $cover  = $project->getFirstMedia('cover');
    $slug   = $project->getTranslation('slug', $locale);
    $title  = $project->getTranslation('title', $locale);
    $summary = $project->getTranslation('summary', $locale);
    $num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
@endphp

{{-- Gerold portfolio card: padded frame, image, gradient overlay slides up on hover/focus --}}
<article class="group relative overflow-hidden rounded-[10px] border border-border bg-surface-muted px-[15px] pt-[25px] transition-colors duration-300 hover:border-accent/60 lg:p-9 lg:pb-0" data-reveal>
    @if ($cover)
        <img
            src="{{ $cover->getUrl() }}"
            alt="{{ $title }}"
            class="aspect-[4/3] w-full rounded-t-[10px] object-cover object-top"
            loading="lazy"
            width="800"
            height="600"
        >
    @else
        <div class="relative flex aspect-[4/3] w-full flex-col items-center justify-center gap-3 overflow-hidden rounded-t-[10px] px-4">
            <div class="absolute inset-0 gradient-primary opacity-25"></div>
            <span class="gradient-text select-none text-7xl font-bold">{{ $num }}</span>
            <span class="relative text-center text-xl font-semibold text-text md:text-2xl">{{ $title }}</span>
        </div>
    @endif

    <div class="invisible absolute bottom-[15px] left-0 w-full translate-y-5 px-[15px] opacity-0 transition-all duration-300 group-hover:visible group-hover:bottom-5 group-hover:translate-y-0 group-hover:opacity-100 group-focus-within:visible group-focus-within:bottom-5 group-focus-within:translate-y-0 group-focus-within:opacity-100 lg:px-5">
        <a href="{{ route('projects.show', $slug) }}"
           class="block w-full rounded-[15px] gradient-primary p-[15px] pr-[30px] text-white lg:p-5 lg:pr-[50px]">
            <span class="mb-2 block text-xl font-bold md:text-[25px] lg:mb-[15px] lg:text-3xl">
                {{ $title }}
            </span>

            @if ($summary)
                <span class="block text-white/80 line-clamp-2">{{ $summary }}</span>
            @endif

            <i class="flaticon-up-right-arrow absolute right-8 top-1/2 -translate-y-1/2 rotate-[-360deg] text-[15px] text-white transition-all duration-300 group-hover:rotate-0 md:text-xl lg:right-[55px]" aria-hidden="true"></i>
        </a>
    </div>
</article>
