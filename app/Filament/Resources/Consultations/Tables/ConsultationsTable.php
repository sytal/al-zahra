<?php

namespace App\Filament\Resources\Consultations\Tables;

use App\Filament\Support\AdminEnum;
use App\Support\Enums\ConsultationStatus;
use App\Support\Enums\ConsultationType;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ConsultationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('topic')
                    ->label(__('admin_ui.l.topic'))
                    ->default('—')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('requester')
                    ->label(__('admin_ui.l.requester'))
                    ->state(fn ($record) => $record->guest_name ?: $record->guest_email ?: $record->user?->name ?: $record->user?->email ?: '—')
                    ->searchable(query: fn ($query, string $search) => $query->where(fn ($q) => $q
                        ->where('guest_name', 'like', "%{$search}%")
                        ->orWhere('guest_email', 'like', "%{$search}%")))
                    ->toggleable(),
                TextColumn::make('type')
                    ->label(__('admin_ui.l.type'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => AdminEnum::label($state))
                    ->color(fn ($state) => AdminEnum::color($state))
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('admin_ui.l.status'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => AdminEnum::label($state))
                    ->color(fn ($state) => AdminEnum::color($state))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('admin_ui.l.received'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('scheduled_datetime')
                    ->label(__('admin_ui.l.scheduled_date_time'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('admin_ui.l.status'))
                    ->options(collect(ConsultationStatus::cases())->mapWithKeys(fn ($case) => [$case->value => AdminEnum::label($case)])->all()),
                SelectFilter::make('type')
                    ->label(__('admin_ui.l.type'))
                    ->options(collect(ConsultationType::cases())->mapWithKeys(fn ($case) => [$case->value => AdminEnum::label($case)])->all()),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
