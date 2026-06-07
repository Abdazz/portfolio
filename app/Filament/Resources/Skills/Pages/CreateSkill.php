<?php

namespace App\Filament\Resources\Skills\Pages;

use App\Filament\Concerns\HasTranslatableContent;
use App\Filament\Resources\Skills\SkillResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSkill extends CreateRecord
{
    use HasTranslatableContent;

    protected static string $resource = SkillResource::class;

    protected function getHeaderActions(): array
    {
        return $this->getLocaleActions();
    }
}
