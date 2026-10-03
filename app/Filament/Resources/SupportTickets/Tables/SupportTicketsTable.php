<?php

namespace App\Filament\Resources\SupportTickets\Tables;

use App\Filament\Support\AdminEnum;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SupportTicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject')
                    ->label(__('admin_ui.l.subject'))
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('requester')
                    ->label(__('admin_ui.l.requester'))
                    ->state(fn ($record) => $record->user?->name ?: $record->user?->email ?: '—')
                    ->toggleable(),
                TextColumn::make('status')
                    ->label(__('admin_ui.l.status'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => AdminEnum::label($state))
                    ->color(fn ($state) => match ($state) {
                        'open' => 'warning',
                        'answered' => 'success',
                        'closed' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('admin_ui.l.received'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('admin_ui.l.status'))
                    ->options(['open' => AdminEnum::label('open'), 'answered' => AdminEnum::label('answered'), 'closed' => AdminEnum::label('closed')]),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
