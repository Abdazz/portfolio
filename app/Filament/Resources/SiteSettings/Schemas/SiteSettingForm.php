<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use App\Services\Home\HomeLayoutRegistry;
use App\Services\Resume\TemplateRegistry;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('SEO'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('meta_title')
                            ->label(__('Meta title'))
                            ->columnSpanFull(),
                        TextInput::make('meta_description')
                            ->label(__('Meta description'))
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('og_image_en')
                            ->collection('og_image_en')
                            ->label(__('OG image (English)'))
                            ->helperText(__('1200×630 px recommended. Used when sharing English pages on social media.'))
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('og_image_fr')
                            ->collection('og_image_fr')
                            ->label(__('OG image (French)'))
                            ->helperText(__('1200×630 px recommended. Used when sharing French pages on social media.'))
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->columnSpanFull(),
                        TextInput::make('twitter_handle')
                            ->label(__('Twitter / X handle'))
                            ->prefix('@'),
                        TextInput::make('contact_email')
                            ->label(__('Contact email'))
                            ->email(),
                    ]),

                Section::make(__('Social links'))
                    ->schema([
                        Repeater::make('social_links')
                            ->schema([
                                TextInput::make('label')->required(),
                                TextInput::make('url')->url()->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel(__('Add social link')),
                    ]),

                Section::make(__('Home page'))
                    ->schema([
                        Select::make('home_layout')
                            ->label(__('Layout'))
                            ->options(fn () => app(HomeLayoutRegistry::class)->selectOptions())
                            ->default('gerold-01')
                            ->required(),
                    ]),

                Section::make(__('Resume'))
                    ->schema([
                        Select::make('resume_template')
                            ->label(__('Template'))
                            ->options(fn () => app(TemplateRegistry::class)->selectOptions())
                            ->default('default')
                            ->required(),
                    ]),
            ]);
    }
}
