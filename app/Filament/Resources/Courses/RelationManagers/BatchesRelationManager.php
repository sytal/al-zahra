<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BatchesRelationManager extends RelationManager
{
    protected static string $relationship = 'batches';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->label(__('admin_ui.l.label'))
                    ->required()
                    ->maxLength(255),

                DatePicker::make('starts_at')
                    ->label(__('admin_ui.l.starts_at'))
                    ->required(),

                DatePicker::make('ends_at')
                    ->label(__('admin_ui.l.ends_at')),

                TextInput::make('seats')
                    ->label(__('admin_ui.l.seats'))
                    ->numeric()
                    ->minValue(1)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                TextColumn::make('label')
                    ->label(__('admin_ui.l.label'))
                    ->searchable(),

                TextColumn::make('starts_at')
                    ->label(__('admin_ui.l.starts_at'))
                    ->date(),

                TextColumn::make('ends_at')
                    ->label(__('admin_ui.l.ends_at'))
                    ->date(),

                TextColumn::make('seats')
                    ->label(__('admin_ui.l.seats')),
            ])
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
