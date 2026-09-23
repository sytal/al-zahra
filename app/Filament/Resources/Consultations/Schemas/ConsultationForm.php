<?php

namespace App\Filament\Resources\Consultations\Schemas;

use App\Support\Enums\ConsultationStatus;
use App\Support\Enums\ConsultationType;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class ConsultationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Request')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('topic')
                            ->label('Topic')
                            ->content(fn ($record) => $record?->topic ?: '—'),
                        Placeholder::make('requester')
                            ->label('Requester')
                            ->content(fn ($record) => $record
                                ? ($record->guest_name ?: $record->guest_email ?: $record->user?->name ?: $record->user?->email ?: '—')
                                : '—'),
                        Placeholder::make('type')
                            ->label('Type')
                            ->content(fn ($record) => $record?->type instanceof ConsultationType ? $record->type->value : '—'),
                        Placeholder::make('created_at')
                            ->label('Requested at')
                            ->content(fn ($record) => $record?->created_at?->toDayDateTimeString() ?? '—'),
                        Textarea::make('question')
                            ->label('Question')
                            ->columnSpanFull()
                            ->rows(4)
                            ->disabled()
                            ->dehydrated(false),
                    ]),
                Section::make('Response')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options(collect(ConsultationStatus::cases())->mapWithKeys(fn ($case) => [$case->value => ucfirst(str_replace('_', ' ', $case->value))]))
                            ->required(),
                        DateTimePicker::make('scheduled_datetime')
                            ->label('Scheduled Date & Time')
                            ->visible(fn (?\App\Modules\Consultation\Models\Consultation $record) => $record?->type === ConsultationType::PAID_BOOKING),
                        Textarea::make('answer')
                            ->label('Answer')
                            ->columnSpanFull()
                            ->rows(6),
                    ]),
            ]);
    }
}
