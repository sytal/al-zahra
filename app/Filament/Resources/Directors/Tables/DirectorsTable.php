<?php

namespace App\Filament\Resources\Directors\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DirectorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('profile_photo')
                    ->label(__('admin_ui.l.photo'))
                    ->circular()
                    ->getStateUsing(fn ($record) => $record->getFirstMediaUrl('profile_photo') ?: null)
                    ->defaultImageUrl(asset('images/avatar-placeholder.svg')),
                TextColumn::make('full_name')
                    ->label(__('admin_ui.l.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('is_published')
                    ->label(__('admin_ui.l.status'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? __('admin_ui.l.published') : __('admin_ui.l.draft'))
                    ->color(fn ($state) => $state ? 'success' : 'gray')
                    ->sortable(),
            ])
            ->defaultSort('full_name')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
