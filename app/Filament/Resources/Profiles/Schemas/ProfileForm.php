<?php

namespace App\Filament\Resources\Profiles\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('profile.sections.basic'))
                    ->columns(2)
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('avatar')
                            ->collection('avatar')
                            ->image()
                            ->imageEditor()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/avif'])
                            ->label('Avatar photo')
                            ->columnSpanFull(),
                        TextInput::make('full_name')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('email')
                            ->email()
                            ->label(__('profile.fields.email')),
                        TextInput::make('phone')
                            ->tel()
                            ->label(__('profile.fields.phone')),
                        TextInput::make('location')
                            ->columnSpanFull()
                            ->label(__('profile.fields.location')),
                    ]),

                Section::make(__('profile.sections.bio'))
                    ->schema([
                        TextInput::make('headline')
                            ->required()
                            ->label(__('profile.fields.headline')),
                        Textarea::make('bio')
                            ->rows(6)
                            ->label(__('profile.fields.bio')),
                    ]),

                Section::make(__('profile.sections.social'))
                    ->schema([
                        KeyValue::make('social_links')
                            ->label(__('profile.fields.social_links'))
                            ->keyLabel(__('profile.fields.social_platform'))
                            ->valueLabel(__('profile.fields.social_url'))
                            ->addActionLabel(__('profile.actions.add_social')),
                    ]),
            ]);
    }
}
