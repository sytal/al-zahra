<?php

namespace App\Filament\Resources\Courses\Tables;

use App\Filament\Support\AdminEnum;
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

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('')
                    ->state(fn ($record) => $record->getFirstMediaUrl('cover_image', 'card') ?: null)
                    ->defaultImageUrl(asset('images/course-placeholder.svg'))
                    ->size(50),

                TextColumn::make('title')
                    ->label(__('admin_ui.l.title'))
                    ->formatStateUsing(fn ($record) => $record->getTranslation('title', app()->getLocale()))
                    ->searchable(query: fn ($query, string $search) => $query->where('title', 'like', "%{$search}%"))
                    ->sortable()
                    ->limit(50),

                TextColumn::make('level')
                    ->label(__('admin_ui.l.level'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => AdminEnum::label($state))
                    ->color(fn ($state) => AdminEnum::color($state))
                    ->sortable(),

                TextColumn::make('audience')
                    ->label(__('admin_ui.l.audience'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => AdminEnum::label($state))
                    ->color(fn ($state) => AdminEnum::color($state))
                    ->sortable(),

                TextColumn::make('enrolled_count')
                    ->label(__('admin_ui.l.enrolled'))
                    ->numeric()
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
                SelectFilter::make('level')
                    ->label(__('admin_ui.l.level'))
                    ->options(collect(\App\Support\Enums\CourseLevel::cases())->mapWithKeys(fn ($c) => [$c->value => AdminEnum::label($c)])->all()),
                SelectFilter::make('audience')
                    ->label(__('admin_ui.l.audience'))
                    ->options(collect(\App\Support\Enums\CourseAudience::cases())->mapWithKeys(fn ($c) => [$c->value => AdminEnum::label($c)])->all()),
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
