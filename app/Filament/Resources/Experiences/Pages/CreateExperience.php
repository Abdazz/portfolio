<?php

namespace App\Filament\Resources\Experiences\Pages;

use App\Filament\Concerns\HasTranslatableContent;
use App\Filament\Resources\Experiences\ExperienceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExperience extends CreateRecord
{
    use HasTranslatableContent;

    protected static string $resource = ExperienceResource::class;

    protected function getHeaderActions(): array
    {
        return $this->getLocaleActions();
    }
}
