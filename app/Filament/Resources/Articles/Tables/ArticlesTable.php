<?php

namespace App\Filament\Resources\Articles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('')
                    ->state(fn ($record) => $record->getFirstMediaUrl('featured_image', 'thumb') ?: null)
                    ->circular(false)
                    ->size(50)
                    ->defaultImageUrl(asset('images/avatar-placeholder.svg')),

                TextColumn::make('title')
                    ->label(__('admin_ui.l.title'))
                    ->formatStateUsing(fn ($record) => $record->getTranslation('title', app()->getLocale()))
                    ->searchable(query: fn ($query, string $search) => $query->where('title', 'like', "%{$search}%"))
                    ->sortable()
                    ->limit(50),

                TextColumn::make('category.name')
                    ->label(__('admin_ui.l.category'))
                    ->formatStateUsing(fn ($state, $record) => $record->category?->getTranslation('name', app()->getLocale()))
                    ->sortable(),

                TextColumn::make('author.name')
                    ->label(__('admin_ui.l.author'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('is_published')
                    ->label(__('admin_ui.l.status'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? __('admin_ui.l.published') : __('admin_ui.l.draft'))
                    ->color(fn ($state) => $state ? 'success' : 'gray')
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label(__('admin_ui.l.published_at'))
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('views_count')
                    ->label(__('admin_ui.l.views'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('category_id')
                    ->label(__('admin_ui.l.category'))
                    ->relationship('category', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->getTranslation('name', app()->getLocale()))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('author_id')
                    ->label(__('admin_ui.l.author'))
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload(),
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
