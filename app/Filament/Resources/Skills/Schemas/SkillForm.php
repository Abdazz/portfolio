<?php

namespace App\Filament\Resources\Skills\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->columnSpanFull(),
                        Select::make('category')
                            ->required()
                            ->options([
                                'frontend' => 'Frontend',
                                'backend' => 'Backend',
                                'database' => 'Database',
                                'devops' => 'DevOps',
                                'mobile' => 'Mobile',
                                'design' => 'Design',
                                'other' => 'Other',
                            ]),
                        Select::make('level')
                            ->options([
                                'beginner' => 'Beginner',
                                'intermediate' => 'Intermediate',
                                'advanced' => 'Advanced',
                                'expert' => 'Expert',
                            ]),
                        TextInput::make('icon')
                            ->placeholder('heroicon-o-star'),
                        TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                    ]),
            ]);
    }
}
