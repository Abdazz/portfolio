@props(['projects'])

@if ($projects->isEmpty())
    <p class="py-12 text-center text-sm text-text-muted">{{ __('projects.no_results') }}</p>
@else
    <div class="grid gap-px bg-border sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($projects as $i => $project)
            <div class="bg-surface">
                <x-molecules.project-card :project="$project" :index="$i" />
            </div>
        @endforeach
    </div>
@endif
