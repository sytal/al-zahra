<?php

namespace App\Filament\Resources\Resources\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ResourcesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('')
                    ->state(fn ($record) => $record->getFirstMediaUrl('thumbnail') ?: null)
                    ->size(50),

                TextColumn::make('title')
                    ->label('Title')
                    ->formatStateUsing(fn ($record) => $record->getTranslation('title', app()->getLocale()))
                    ->searchable(query: fn ($query, string $search) => $query->where('title', 'like', "%{$search}%"))
                    ->sortable()
                    ->limit(50),

                BadgeColumn::make('resource_type')
                    ->label('Type'),

                BadgeColumn::make('is_free')
                    ->label('Access')
                    ->formatStateUsing(fn (bool $state) => $state ? 'Free' : 'Paid')
                    ->color(fn (bool $state) => $state ? 'success' : 'warning'),

                TextColumn::make('download_count')
                    ->label('Downloads')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('is_published')
                    ->label('Status')
                    ->options([1 => 'Published', 0 => 'Draft']),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
