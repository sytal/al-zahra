<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use App\Filament\Support\TranslatableTabs;
use App\Support\Enums\LessonContentType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TranslatableTabs::make('title_tabs', ['title'], fn (string $locale, string $field) => TextInput::make("{$field}.{$locale}")
                    ->label('Title')
                    ->required($locale === config('app.fallback_locale', 'en'))
                    ->maxLength(255)),

                Select::make('content_type')
                    ->label('Content type')
                    ->options(collect(LessonContentType::cases())->mapWithKeys(fn (LessonContentType $type) => [$type->value => ucfirst($type->value)]))
                    ->required(),

                TextInput::make('duration_minutes')
                    ->label('Duration (minutes)')
                    ->numeric()
                    ->minValue(0),

                Toggle::make('is_preview')
                    ->label('Free preview'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->formatStateUsing(fn ($record) => $record->getTranslation('title', app()->getLocale()))
                    ->searchable(),

                TextColumn::make('content_type')
                    ->badge(),

                TextColumn::make('duration_minutes')
                    ->label('Duration (min)'),

                IconColumn::make('is_preview')
                    ->label('Preview')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
