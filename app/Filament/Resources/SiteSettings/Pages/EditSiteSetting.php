<?php

namespace App\Filament\Resources\SiteSettings\Pages;

use App\Filament\Concerns\HasTranslatableContent;
use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Models\SiteSetting;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSiteSetting extends EditRecord
{
    use HasTranslatableContent;

    protected static string $resource = SiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return $this->getLocaleActions();
    }

    protected function resolveRecord(int|string $key): Model
    {
        return SiteSetting::instance();
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
