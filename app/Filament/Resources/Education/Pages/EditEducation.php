<?php

namespace App\Filament\Resources\Education\Pages;

use App\Filament\Concerns\HasTranslatableContent;
use App\Filament\Resources\Education\EducationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditEducation extends EditRecord
{
    use HasTranslatableContent;

    protected static string $resource = EducationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getLocaleActions(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
