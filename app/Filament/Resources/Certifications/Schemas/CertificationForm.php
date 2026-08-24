<?php

namespace App\Filament\Resources\Certifications\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CertificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('badge')
                            ->collection('badge')
                            ->image()
                            ->imageEditor()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/avif'])
                            ->label('Badge image')
                            ->columnSpanFull(),
                        TextInput::make('title')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('issuer')
                            ->required(),
                        DatePicker::make('issued_at')
                            ->native(false),
                        TextInput::make('credential_url')
                            ->url()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
