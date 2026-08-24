<?php

namespace App\Filament\Resources\Certifications\Pages;

use App\Filament\Concerns\HasTranslatableContent;
use App\Filament\Resources\Certifications\CertificationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCertifications extends ListRecords
{
    use HasTranslatableContent;

    protected static string $resource = CertificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
