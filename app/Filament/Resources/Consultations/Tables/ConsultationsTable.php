<?php

namespace App\Filament\Resources\Consultations\Tables;

use App\Support\Enums\ConsultationStatus;
use App\Support\Enums\ConsultationType;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
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
                    ->label('Topic')
                    ->default('—')
                    ->searchable(),
                TextColumn::make('requester')
                    ->label('Requester')
                    ->state(fn ($record) => $record->guest_name ?: $record->guest_email ?: $record->user?->name ?: $record->user?->email ?: '—'),
                BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'info' => ConsultationType::FREE_QUESTION->value,
                        'warning' => ConsultationType::PAID_BOOKING->value,
                    ])
                    ->formatStateUsing(fn (ConsultationType $state) => ucfirst(str_replace('_', ' ', $state->value))),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => ConsultationStatus::PENDING->value,
                        'success' => ConsultationStatus::ANSWERED->value,
                        'info' => ConsultationStatus::SCHEDULED->value,
                        'primary' => ConsultationStatus::COMPLETED->value,
                        'danger' => ConsultationStatus::CANCELLED->value,
                    ])
                    ->formatStateUsing(fn (ConsultationStatus $state) => ucfirst($state->value)),
                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(ConsultationStatus::cases())->mapWithKeys(fn ($case) => [$case->value => ucfirst($case->value)])),
                SelectFilter::make('type')
                    ->options(collect(ConsultationType::cases())->mapWithKeys(fn ($case) => [$case->value => ucfirst(str_replace('_', ' ', $case->value))])),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
