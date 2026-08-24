<div class="space-y-8">
    {{-- Filters bar --}}
    <div class="flex flex-wrap items-center gap-3">
        {{-- Search --}}
        <div class="relative flex-1 min-w-48">
            <flux:icon name="magnifying-glass" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-text-muted pointer-events-none" />
            <input
                type="search"
                wire:model.live.debounce.400ms="search"
                placeholder="{{ __('projects.search') }}"
                class="w-full rounded-lg border border-border bg-surface pl-9 pr-3 py-2 text-sm text-text placeholder:text-text-muted focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-colors"
            >
        </div>

        {{-- Featured filter --}}
        <div class="flex items-center gap-1 rounded-lg border border-border bg-surface p-1">
            <button
                wire:click="$set('filter', 'all')"
                class="rounded-md px-3 py-1 text-sm font-medium transition-colors {{ $filter === 'all' ? 'bg-accent text-white' : 'text-text-muted hover:text-text' }}"
            >
                {{ __('projects.all') }}
            </button>
            <button
                wire:click="$set('filter', 'featured')"
                class="rounded-md px-3 py-1 text-sm font-medium transition-colors {{ $filter === 'featured' ? 'bg-accent text-white' : 'text-text-muted hover:text-text' }}"
            >
                {{ __('projects.featured') }}
            </button>
        </div>

        {{-- Tech stack filter --}}
        @if ($allTech->isNotEmpty())
            <select
                wire:model.live="tech"
                class="rounded-lg border border-border bg-surface px-3 py-2 text-sm text-text focus:outline-none focus:ring-2 focus:ring-accent transition-colors"
            >
                <option value="">{{ __('projects.filter_tech') }}</option>
                @foreach ($allTech as $t)
                    <option value="{{ $t }}" @selected($tech === $t)>{{ $t }}</option>
                @endforeach
            </select>
        @endif
    </div>

    {{-- Results --}}
    <div wire:loading.class="opacity-60 transition-opacity">
        @if ($projects->isEmpty())
            <p class="py-16 text-center text-text-muted">{{ __('projects.no_results') }}</p>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($projects as $project)
                    <x-molecules.project-card :project="$project" wire:key="project-{{ $project->id }}" />
                @endforeach
            </div>

            @if ($projects->hasPages())
                <div class="mt-8">
                    {{ $projects->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
