<?php

namespace App\Filament\Resources\LanguageSpokens\Pages;

use App\Filament\Concerns\HasTranslatableContent;
use App\Filament\Resources\LanguageSpokens\LanguageSpokenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLanguageSpokens extends ListRecords
{
    use HasTranslatableContent;

    protected static string $resource = LanguageSpokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
