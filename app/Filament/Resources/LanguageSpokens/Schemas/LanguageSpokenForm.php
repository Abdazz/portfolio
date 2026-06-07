<?php

namespace App\Filament\Resources\LanguageSpokens\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LanguageSpokenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        Select::make('level')
                            ->required()
                            ->options([
                                'A1' => 'A1 – Beginner',
                                'A2' => 'A2 – Elementary',
                                'B1' => 'B1 – Intermediate',
                                'B2' => 'B2 – Upper Intermediate',
                                'C1' => 'C1 – Advanced',
                                'C2' => 'C2 – Proficient',
                                'native' => 'Native',
                            ]),
                    ]),
            ]);
    }
}
