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

    <article class="py-16 md:py-24">
        <div class="mx-auto max-w-4xl px-6 lg:px-8">

            {{-- Back + breadcrumb --}}
            <div data-reveal>
                <x-molecules.breadcrumb :items="[
                    ['label' => __('nav.home'), 'href' => route('home')],
                    ['label' => __('nav.projects'), 'href' => route('projects.index')],
                    ['label' => $title],
                ]" />
            </div>

            {{-- Header --}}
            <header class="mt-8 flex flex-col gap-6" data-reveal>
                <span class="inline-flex items-center gap-2.5 text-sm font-medium uppercase tracking-[0.2em] text-accent-content">
                    <span class="h-px w-8 bg-accent/50" aria-hidden="true"></span>
                    {{ __('nav.projects') }}
                </span>

                <h1 class="gradient-text text-4xl font-semibold leading-[1.1] md:text-5xl lg:text-6xl">
                    {{ $title }}
                </h1>

                @if ($summary)
                    <p class="max-w-2xl text-lg leading-relaxed text-text-muted">{{ $summary }}</p>
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
                <div class="mt-12 overflow-hidden rounded-2xl border border-border" data-reveal>
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
                <div class="mt-12 prose prose-zinc dark:prose-invert max-w-none prose-headings:font-display prose-headings:font-semibold prose-a:text-accent-content prose-a:no-underline hover:prose-a:underline" data-reveal>
                    {!! $body !!}
                </div>
            @endif

            {{-- Gallery --}}
            @if ($gallery->isNotEmpty())
                <div class="mt-16 flex flex-col gap-6" data-reveal>
                    <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-accent-content">{{ __('projects.gallery') }}</h2>
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ($gallery as $image)
                            <div class="overflow-hidden rounded-2xl border border-border bg-surface-muted">
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
            <div class="mt-16 border-t border-border/50 pt-8">
                <x-atoms.link href="{{ route('projects.index') }}">
                    ← {{ __('projects.back') }}
                </x-atoms.link>
            </div>

        </div>
    </article>

</x-layouts.public>
