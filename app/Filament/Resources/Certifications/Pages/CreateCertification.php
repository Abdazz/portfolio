<?php

namespace App\Filament\Resources\Certifications\Pages;

use App\Filament\Concerns\HasTranslatableContent;
use App\Filament\Resources\Certifications\CertificationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCertification extends CreateRecord
{
    use HasTranslatableContent;

    protected static string $resource = CertificationResource::class;

    protected function getHeaderActions(): array
    {
        return $this->getLocaleActions();
    }
}
