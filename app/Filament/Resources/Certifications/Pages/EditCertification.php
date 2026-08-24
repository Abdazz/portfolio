<?php

namespace App\Filament\Resources\Certifications\Pages;

use App\Filament\Concerns\HasTranslatableContent;
use App\Filament\Resources\Certifications\CertificationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCertification extends EditRecord
{
    use HasTranslatableContent;

    protected static string $resource = CertificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getLocaleActions(),
            DeleteAction::make(),
        ];
    }
}
