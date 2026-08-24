<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Support\JsonQuery;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectFilters extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'tech')]
    public string $tech = '';

    #[Url(as: 'filter')]
    public string $filter = 'all'; // 'all' | 'featured'

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedTech(): void
    {
        $this->resetPage();
    }

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    /** @return Collection<int, string> */
    public function getAllTechStack(): Collection
    {
        return Project::pluck('tech_stack')
            ->flatten()
            ->unique()
            ->sort()
            ->values();
    }

    public function render(): View
    {
        $locale = app()->getLocale();

        $query = Project::query()
            ->when($this->filter === 'featured', fn ($q) => $q->where('featured', true))
            ->when(filled($this->tech), fn ($q) => $q->whereJsonContains('tech_stack', $this->tech))
            ->when(filled($this->search), function ($q) use ($locale) {
                $titleExpr = JsonQuery::ilike('title', $locale);
                $summaryExpr = JsonQuery::ilike('summary', $locale);
                $q->where(function ($sub) use ($titleExpr, $summaryExpr) {
                    $sub->whereRaw("{$titleExpr} like ?", ['%'.mb_strtolower($this->search).'%'])
                        ->orWhereRaw("{$summaryExpr} like ?", ['%'.mb_strtolower($this->search).'%']);
                });
            })
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->with('media');

        $projects = $query->paginate(9);

        return view('livewire.projects.project-filters', [
            'projects' => $projects,
            'allTech' => $this->getAllTechStack(),
        ]);
    }
}
