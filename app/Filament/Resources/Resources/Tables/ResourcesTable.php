<?php

namespace App\Filament\Resources\Resources\Tables;

use App\Filament\Support\AdminEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
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
                    ->defaultImageUrl(asset('images/resource-placeholder.svg'))
                    ->size(50),

                TextColumn::make('title')
                    ->label(__('admin_ui.l.title'))
                    ->formatStateUsing(fn ($record) => $record->getTranslation('title', app()->getLocale()))
                    ->searchable(query: fn ($query, string $search) => $query->where('title', 'like', "%{$search}%"))
                    ->sortable()
                    ->limit(50),

                TextColumn::make('resource_type')
                    ->label(__('admin_ui.l.type'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => AdminEnum::label($state))
                    ->color(fn ($state) => AdminEnum::color($state))
                    ->sortable(),

                TextColumn::make('is_free')
                    ->label(__('admin_ui.l.access'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? __('admin_ui.l.free') : __('admin_ui.l.paid'))
                    ->color(fn ($state) => $state ? 'success' : 'warning'),

                TextColumn::make('download_count')
                    ->label(__('admin_ui.l.downloads'))
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('category_id')
                    ->label(__('admin_ui.l.category'))
                    ->relationship('category', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->getTranslation('name', app()->getLocale()))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('resource_type')
                    ->label(__('admin_ui.l.type'))
                    ->options(collect(\App\Support\Enums\ResourceType::cases())->mapWithKeys(fn ($c) => [$c->value => AdminEnum::label($c)])->all()),
                TernaryFilter::make('is_free')
                    ->label(__('admin_ui.l.free')),
                SelectFilter::make('is_published')
                    ->label(__('admin_ui.l.status'))
                    ->options([1 => __('admin_ui.l.published'), 0 => __('admin_ui.l.draft')]),
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
