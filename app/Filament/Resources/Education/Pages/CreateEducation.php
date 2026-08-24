<?php

namespace App\Filament\Resources\Education\Pages;

use App\Filament\Concerns\HasTranslatableContent;
use App\Filament\Resources\Education\EducationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEducation extends CreateRecord
{
    use HasTranslatableContent;

    protected static string $resource = EducationResource::class;

    protected function getHeaderActions(): array
    {
        return $this->getLocaleActions();
    }
}
