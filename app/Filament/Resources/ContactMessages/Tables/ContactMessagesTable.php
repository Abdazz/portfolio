<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                IconColumn::make('is_read')
                    ->label(__('Read'))
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('gray')
                    ->falseColor('warning')
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('subject')
                    ->limit(40)
                    ->placeholder('—'),
                TextColumn::make('locale')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'en' => 'info',
                        'fr' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('ip_address')
                    ->label(__('IP'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_read')
                    ->label(__('Read status'))
                    ->trueLabel(__('Read'))
                    ->falseLabel(__('Unread')),
            ])
            ->recordActions([
                Action::make('view')
                    ->label(__('View'))
                    ->icon('heroicon-o-eye')
                    ->modalContent(fn ($record) => view('filament.contact-messages.view-modal', ['message' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel(__('Close'))
                    ->after(fn ($record) => $record->is_read ?: $record->markAsRead()),
                Action::make('mark_read')
                    ->label(__('Mark as read'))
                    ->icon('heroicon-o-envelope-open')
                    ->hidden(fn ($record) => $record->is_read)
                    ->action(fn ($record) => $record->markAsRead())
                    ->requiresConfirmation(false),
            ]);
    }
}
