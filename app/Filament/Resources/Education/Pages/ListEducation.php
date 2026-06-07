<?php

namespace App\Filament\Resources\Education\Pages;

use App\Filament\Concerns\HasTranslatableContent;
use App\Filament\Resources\Education\EducationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEducation extends ListRecords
{
    use HasTranslatableContent;

    protected static string $resource = EducationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
