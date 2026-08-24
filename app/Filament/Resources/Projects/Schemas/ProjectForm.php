<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Content'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('summary')
                            ->rows(3)
                            ->columnSpanFull(),
                        RichEditor::make('body')
                            ->columnSpanFull()
                            ->fileAttachmentsDisk('public'),
                    ]),

                Section::make(__('Media'))
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('cover')
                            ->collection('cover')
                            ->image()
                            ->imageEditor()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/avif'])
                            ->label(__('Cover image')),
                        TextInput::make('cover_alt')
                            ->label(__('Cover image alt text'))
                            ->helperText(__('Describe the image for screen readers (required for accessibility).'))
                            ->required()
                            ->maxLength(255),
                        SpatieMediaLibraryFileUpload::make('gallery')
                            ->collection('gallery')
                            ->image()
                            ->imageEditor()
                            ->multiple()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/avif'])
                            ->label(__('Gallery')),
                    ]),

                Section::make(__('Details'))
                    ->columns(2)
                    ->schema([
                        TagsInput::make('tech_stack')
                            ->columnSpanFull(),
                        Repeater::make('links')
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('label')
                                    ->required(),
                                TextInput::make('url')
                                    ->url()
                                    ->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(0),
                        Toggle::make('featured'),
                        TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                    ]),
            ]);
    }
}
