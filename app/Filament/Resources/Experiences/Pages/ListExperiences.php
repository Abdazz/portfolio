<?php

namespace App\Filament\Resources\Experiences\Pages;

use App\Filament\Concerns\HasTranslatableContent;
use App\Filament\Resources\Experiences\ExperienceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExperiences extends ListRecords
{
    use HasTranslatableContent;

    protected static string $resource = ExperienceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
