<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Concerns\HasTranslatableContent;
use App\Filament\Resources\Projects\ProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    use HasTranslatableContent;

    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getLocaleActions(),
        ];
    }
}
