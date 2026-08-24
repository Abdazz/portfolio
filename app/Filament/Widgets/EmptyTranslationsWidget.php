<?php

namespace App\Filament\Widgets;

use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\LanguageSpoken;
use App\Models\Project;
use App\Models\Skill;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmptyTranslationsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 10;

    protected function getStats(): array
    {
        $missingFr = [
            'experiences' => Experience::whereRaw("(title->>'fr') IS NULL OR (title->>'fr') = ''")->count(),
            'education' => Education::whereRaw("(degree->>'fr') IS NULL OR (degree->>'fr') = ''")->count(),
            'skills' => Skill::whereRaw("(name->>'fr') IS NULL OR (name->>'fr') = ''")->count(),
            'certs' => Certification::whereRaw("(title->>'fr') IS NULL OR (title->>'fr') = ''")->count(),
            'languages' => LanguageSpoken::whereRaw("(name->>'fr') IS NULL OR (name->>'fr') = ''")->count(),
            'projects' => Project::whereRaw("(title->>'fr') IS NULL OR (title->>'fr') = ''")->count(),
        ];

        $totalMissing = array_sum($missingFr);

        $description = $totalMissing > 0
            ? implode(', ', array_filter(array_map(
                fn ($label, $count) => $count > 0 ? "{$count} {$label}" : null,
                array_keys($missingFr),
                $missingFr,
            )))
            : 'All records translated';

        return [
            Stat::make(__('Missing FR Translations'), $totalMissing)
                ->description($description)
                ->color($totalMissing > 0 ? 'warning' : 'success')
                ->icon($totalMissing > 0 ? 'heroicon-o-language' : 'heroicon-o-check-circle'),
        ];
    }
}
