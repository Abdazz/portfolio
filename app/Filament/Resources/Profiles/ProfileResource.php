<?php

namespace App\Filament\Resources\Profiles;

use App\Filament\Resources\Profiles\Pages\EditProfile;
use App\Filament\Resources\Profiles\Schemas\ProfileForm;
use App\Models\Profile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ProfileResource extends Resource
{
    protected static ?string $model = Profile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ProfileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'edit' => EditProfile::route('/{record}/edit'),
        ];
    }

    public static function getNavigationUrl(): string
    {
        return static::getUrl('edit', ['record' => 1]);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public static function getIndexUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?Model $tenant = null, bool $shouldGuessMissingParameters = false): string
    {
        return static::getUrl('edit', ['record' => 1], $isAbsolute, $panel, $tenant);
    }
}
