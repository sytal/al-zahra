<?php

namespace App\Filament\Resources\ResearchPapers\Tables;

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

class ResearchPapersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('')
                    ->state(fn ($record) => $record->getFirstMediaUrl('cover_image') ?: null)
                    ->defaultImageUrl(asset('images/research-placeholder.svg'))
                    ->size(50),

                TextColumn::make('title')
                    ->label(__('admin_ui.l.title'))
                    ->formatStateUsing(fn ($record) => $record->getTranslation('title', app()->getLocale()))
                    ->searchable(query: fn ($query, string $search) => $query->where('title', 'like', "%{$search}%"))
                    ->sortable()
                    ->limit(50),

                TextColumn::make('category.name')
                    ->label(__('admin_ui.l.category'))
                    ->formatStateUsing(fn ($state, $record) => $record->category?->getTranslation('name', app()->getLocale())),

                TextColumn::make('published_year')
                    ->label(__('admin_ui.l.year'))
                    ->sortable(),

                TextColumn::make('is_published')
                    ->label(__('admin_ui.l.status'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? __('admin_ui.l.published') : __('admin_ui.l.draft'))
                    ->color(fn ($state) => $state ? 'success' : 'gray')
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
