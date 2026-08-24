<?php

namespace App\Filament\Resources\LanguageSpokens;

use App\Filament\Resources\LanguageSpokens\Pages\CreateLanguageSpoken;
use App\Filament\Resources\LanguageSpokens\Pages\EditLanguageSpoken;
use App\Filament\Resources\LanguageSpokens\Pages\ListLanguageSpokens;
use App\Filament\Resources\LanguageSpokens\Schemas\LanguageSpokenForm;
use App\Filament\Resources\LanguageSpokens\Tables\LanguageSpokensTable;
use App\Models\LanguageSpoken;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LanguageSpokenResource extends Resource
{
    protected static ?string $model = LanguageSpoken::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLanguage;

    protected static string|UnitEnum|null $navigationGroup = 'Career';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return LanguageSpokenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LanguageSpokensTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLanguageSpokens::route('/'),
            'create' => CreateLanguageSpoken::route('/create'),
            'edit' => EditLanguageSpoken::route('/{record}/edit'),
        ];
    }
}
