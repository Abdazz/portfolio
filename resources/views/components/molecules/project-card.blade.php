@props(['project', 'index' => 0])

@php
    $locale = app()->getLocale();
    $cover  = $project->getFirstMedia('cover');
    $slug   = $project->getTranslation('slug', $locale);
    $title  = $project->getTranslation('title', $locale);
    $summary = $project->getTranslation('summary', $locale);
    $techStack = $project->tech_stack ?? [];
    $num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
@endphp

<article class="group relative flex flex-col overflow-hidden rounded-2xl border border-border bg-surface-muted transition-all duration-300 hover:border-accent/40 hover:-translate-y-1" data-reveal>
    @if ($cover)
        <div class="aspect-video overflow-hidden">
            <img
                src="{{ $cover->getUrl() }}"
                alt="{{ $title }}"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.05]"
                loading="lazy"
                width="800"
                height="450"
            >
        </div>
    @else
        <div class="relative flex aspect-video items-center justify-center overflow-hidden">
            <div class="absolute inset-0 gradient-primary opacity-[0.12]"></div>
            <span class="select-none text-6xl font-semibold text-accent/25">{{ $num }}</span>
        </div>
    @endif

    <div class="flex flex-1 flex-col gap-3 p-6">
        <div class="flex items-start justify-between gap-3">
            <h3 class="text-lg font-medium leading-snug text-text transition-colors group-hover:text-accent-content">
                <a href="{{ route('projects.show', $slug) }}" class="stretched-link">
                    {{ $title }}
                </a>
            </h3>
            <span class="mt-0.5 shrink-0 text-xs font-medium tabular-nums text-text-muted/50">{{ $num }}</span>
        </div>

        @if ($summary)
            <p class="text-sm text-text-muted leading-relaxed line-clamp-2">{{ $summary }}</p>
        @endif

        @if (count($techStack))
            <div class="mt-auto flex flex-wrap gap-1.5 pt-2">
                @foreach (array_slice($techStack, 0, 4) as $tech)
                    <x-atoms.badge>{{ $tech }}</x-atoms.badge>
                @endforeach
                @if (count($techStack) > 4)
                    <x-atoms.badge>+{{ count($techStack) - 4 }}</x-atoms.badge>
                @endif
            </div>
        @endif
    </div>
</article>
