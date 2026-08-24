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

<article class="group relative flex flex-col overflow-hidden border border-border bg-surface-muted hover:border-accent/40 transition-all duration-300">
    @if ($cover)
        <div class="aspect-video overflow-hidden bg-surface">
            <img
                src="{{ $cover->getUrl() }}"
                alt="{{ $title }}"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.04]"
                loading="lazy"
                width="800"
                height="450"
            >
        </div>
    @else
        <div class="aspect-video bg-surface flex items-center justify-center relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(245,158,11,0.05)_0%,transparent_70%)]"></div>
            <span class="font-display text-5xl font-semibold text-border select-none" style="font-optical-sizing: auto;">{{ $num }}</span>
        </div>
    @endif

    <div class="flex flex-1 flex-col gap-3 p-5">
        <div class="flex items-start justify-between gap-3">
            <h3 class="font-display text-lg font-semibold leading-snug text-text group-hover:text-accent transition-colors" style="font-optical-sizing: auto;">
                <a href="{{ route('projects.show', $slug) }}" class="stretched-link">
                    {{ $title }}
                </a>
            </h3>
            <span class="shrink-0 text-xs font-semibold tabular-nums text-text-muted/50 mt-0.5">{{ $num }}</span>
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
