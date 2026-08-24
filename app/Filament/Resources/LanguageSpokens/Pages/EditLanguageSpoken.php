<?php

namespace App\Filament\Resources\LanguageSpokens\Pages;

use App\Filament\Concerns\HasTranslatableContent;
use App\Filament\Resources\LanguageSpokens\LanguageSpokenResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLanguageSpoken extends EditRecord
{
    use HasTranslatableContent;

    protected static string $resource = LanguageSpokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getLocaleActions(),
            DeleteAction::make(),
        ];
    }
}
