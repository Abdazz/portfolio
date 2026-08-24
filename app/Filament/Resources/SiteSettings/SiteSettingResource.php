<?php

namespace App\Filament\Resources\SiteSettings;

use App\Filament\Resources\SiteSettings\Pages\EditSiteSetting;
use App\Filament\Resources\SiteSettings\Schemas\SiteSettingForm;
use App\Models\SiteSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?int $navigationSort = 99;

    public static function form(Schema $schema): Schema
    {
        return SiteSettingForm::configure($schema);
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
            'edit' => EditSiteSetting::route('/{record}/edit'),
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
