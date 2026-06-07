<?php

namespace App\Filament\Resources\Profiles\Pages;

use App\Filament\Concerns\HasTranslatableContent;
use App\Filament\Resources\Profiles\ProfileResource;
use App\Models\Profile;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditProfile extends EditRecord
{
    use HasTranslatableContent;

    protected static string $resource = ProfileResource::class;

    protected function getHeaderActions(): array
    {
        return $this->getLocaleActions();
    }

    protected function resolveRecord(int|string $key): Model
    {
        return Profile::firstOrCreate(['id' => 1], [
            'full_name' => config('admin.name', 'Portfolio Admin'),
            'email' => config('admin.email', ''),
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
