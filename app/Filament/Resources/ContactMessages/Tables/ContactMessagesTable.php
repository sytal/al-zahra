<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Filament\Support\AdminEnum;
use App\Support\Enums\ContactMessageStatus;
use Filament\Actions\EditAction;
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
                    ->label(__('admin_ui.l.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('admin_ui.l.email'))
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('subject')
                    ->label(__('admin_ui.l.subject'))
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('admin_ui.l.status'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => AdminEnum::label($state))
                    ->color(fn ($state) => AdminEnum::color($state))
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
                    ->options(collect(ContactMessageStatus::cases())->mapWithKeys(fn ($case) => [$case->value => AdminEnum::label($case)])->all()),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
