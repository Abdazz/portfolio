<?php

namespace App\Filament\Resources\LanguageSpokens\Pages;

use App\Filament\Concerns\HasTranslatableContent;
use App\Filament\Resources\LanguageSpokens\LanguageSpokenResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLanguageSpoken extends CreateRecord
{
    use HasTranslatableContent;

    protected static string $resource = LanguageSpokenResource::class;

    protected function getHeaderActions(): array
    {
        return $this->getLocaleActions();
    }
}
