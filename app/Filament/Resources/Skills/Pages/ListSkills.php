<?php

namespace App\Filament\Resources\Skills\Pages;

use App\Filament\Concerns\HasTranslatableContent;
use App\Filament\Resources\Skills\SkillResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSkills extends ListRecords
{
    use HasTranslatableContent;

    protected static string $resource = SkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
