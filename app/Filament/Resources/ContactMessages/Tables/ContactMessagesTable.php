<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Support\Enums\ContactMessageStatus;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('subject')
                    ->label('Subject')
                    ->searchable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => ContactMessageStatus::NEW->value,
                        'info' => ContactMessageStatus::READ->value,
                        'success' => ContactMessageStatus::REPLIED->value,
                    ])
                    ->formatStateUsing(fn (ContactMessageStatus $state) => ucfirst($state->value)),
                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(ContactMessageStatus::cases())->mapWithKeys(fn ($case) => [$case->value => ucfirst($case->value)])),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
