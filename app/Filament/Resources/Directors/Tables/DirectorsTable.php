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
                    ->label('Photo')
                    ->circular()
                    ->getStateUsing(fn ($record) => $record->getFirstMediaUrl('profile_photo') ?: null),
                TextColumn::make('full_name')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
