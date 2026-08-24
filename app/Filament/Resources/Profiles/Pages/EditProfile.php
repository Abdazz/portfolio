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

    /**
     * This is a singleton resource: there is only ever one profile row, and its
     * edit URL is always /admin/profiles/1/edit regardless of the row's actual
     * id. Resolve by Profile::first() rather than a fixed id, since `id` is not
     * fillable and a firstOrCreate(['id' => 1], ...) can't force that id via
     * mass assignment — it would silently create a duplicate row instead.
     */
    protected function resolveRecord(int|string $key): Model
    {
        if ($profile = Profile::first()) {
            return $profile;
        }

        return Profile::create([
            'full_name' => config('admin.name', 'Portfolio Admin'),
            'email' => config('admin.email', ''),
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
