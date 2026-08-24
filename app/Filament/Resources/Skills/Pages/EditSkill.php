<?php

namespace App\Filament\Resources\Skills\Pages;

use App\Filament\Concerns\HasTranslatableContent;
use App\Filament\Resources\Skills\SkillResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSkill extends EditRecord
{
    use HasTranslatableContent;

    protected static string $resource = SkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getLocaleActions(),
            DeleteAction::make(),
        ];
    }
}
