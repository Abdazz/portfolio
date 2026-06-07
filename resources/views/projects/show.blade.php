@php
    $locale = app()->getLocale();
    $title   = $project->getTranslation('title', $locale);
    $summary = $project->getTranslation('summary', $locale);
    $body    = $project->getTranslation('body', $locale);
    $cover   = $project->getFirstMedia('cover');
    $gallery = $project->getMedia('gallery');
    $techStack = $project->tech_stack ?? [];
    $links = $project->links ?? [];
@endphp

<x-layouts.public :title="$title" :description="$summary">
    <x-slot:head>
        <x-atoms.json-ld type="CreativeWork" :project="$project" />
    </x-slot:head>

    <article class="space-y-12">

        {{-- Back + breadcrumb --}}
        <div class="animate-enter">
            <x-molecules.breadcrumb :items="[
                ['label' => __('nav.home'), 'href' => route('home')],
                ['label' => __('nav.projects'), 'href' => route('projects.index')],
                ['label' => $title],
            ]" />
        </div>

        {{-- Header --}}
        <header class="space-y-6 animate-enter animate-enter-delay-1">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-accent">{{ __('nav.projects') }}</p>

            <h1 class="font-display font-semibold leading-tight text-text" style="font-size: clamp(2rem, 5vw, 3.75rem); font-optical-sizing: auto;">
                {{ $title }}
            </h1>

            @if ($summary)
                <p class="max-w-2xl text-lg text-text-muted leading-relaxed">{{ $summary }}</p>
            @endif

            <div class="flex flex-wrap items-center gap-4 pt-2">
                @if (count($techStack))
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($techStack as $tech)
                            <x-atoms.badge>{{ $tech }}</x-atoms.badge>
                        @endforeach
                    </div>
                @endif

                @if (count($links))
                    <div class="flex flex-wrap items-center gap-2">
                        @foreach ($links as $link)
                            <x-atoms.button
                                href="{{ $link['url'] }}"
                                variant="secondary"
                                icon="arrow-top-right-on-square"
                                :external="true"
                            >
                                {{ $link['label'] }}
                            </x-atoms.button>
                        @endforeach
                    </div>
                @endif
            </div>
        </header>

        {{-- Cover image --}}
        @if ($cover)
            <div class="animate-enter animate-enter-delay-2 overflow-hidden border border-border">
                <img
                    src="{{ $cover->getUrl() }}"
                    alt="{{ $title }}"
                    class="w-full object-cover"
                    loading="eager"
                >
            </div>
        @endif

        {{-- Body --}}
        @if ($body)
            <div class="animate-enter animate-enter-delay-3 prose prose-zinc dark:prose-invert max-w-none prose-headings:font-display prose-headings:font-semibold prose-a:text-accent prose-a:no-underline hover:prose-a:underline">
                {!! $body !!}
            </div>
        @endif

        {{-- Gallery --}}
        @if ($gallery->isNotEmpty())
            <div class="space-y-6 animate-enter animate-enter-delay-4">
                <div class="flex items-baseline gap-4 border-b border-border pb-4">
                    <h2 class="font-display text-sm font-semibold uppercase tracking-widest text-text" style="font-optical-sizing: auto;">Gallery</h2>
                </div>
                <div class="grid gap-px bg-border sm:grid-cols-2">
                    @foreach ($gallery as $image)
                        <div class="overflow-hidden bg-surface">
                            <img
                                src="{{ $image->getUrl() }}"
                                alt="{{ $title }}"
                                class="w-full object-cover transition-transform duration-500 hover:scale-[1.03]"
                                loading="lazy"
                            >
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Back link --}}
        <div class="border-t border-border/50 pt-8">
            <x-atoms.link href="{{ route('projects.index') }}">
                ← {{ __('projects.back') }}
            </x-atoms.link>
        </div>

    </article>

</x-layouts.public>
