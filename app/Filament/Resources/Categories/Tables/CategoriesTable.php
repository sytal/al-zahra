<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Filament\Support\AdminEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('admin_ui.l.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('admin_ui.l.type'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => AdminEnum::label($state))
                    ->color('info')
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable(),
            ])
            ->defaultSort('type')
            ->filters([
                SelectFilter::make('type')
                    ->label(__('admin_ui.l.type'))
                    ->options(collect(\App\Support\Enums\CategoryType::cases())->mapWithKeys(fn ($c) => [$c->value => AdminEnum::label($c)])->all()),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
