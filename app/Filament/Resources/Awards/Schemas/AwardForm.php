<?php

namespace App\Filament\Resources\Awards\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AwardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('issuer')
                            ->required(),
                        DatePicker::make('awarded_at')
                            ->native(false),
                        TextInput::make('url')
                            ->url()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
