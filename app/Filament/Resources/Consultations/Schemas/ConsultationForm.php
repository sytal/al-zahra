<?php

namespace App\Filament\Resources\Consultations\Schemas;

use App\Filament\Support\AdminEnum;
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
                Section::make(__('admin_ui.l.request'))
                    ->columns(2)
                    ->schema([
                        Placeholder::make('topic')
                            ->label(__('admin_ui.l.topic'))
                            ->content(fn ($record) => $record?->topic ?: '—'),
                        Placeholder::make('requester')
                            ->label(__('admin_ui.l.requester'))
                            ->content(fn ($record) => $record
                                ? ($record->guest_name ?: $record->guest_email ?: $record->user?->name ?: $record->user?->email ?: '—')
                                : '—'),
                        Placeholder::make('type')
                            ->label(__('admin_ui.l.type'))
                            ->content(fn ($record) => $record?->type instanceof ConsultationType ? $record->type->value : '—'),
                        Placeholder::make('created_at')
                            ->label(__('admin_ui.l.requested_at'))
                            ->content(fn ($record) => $record?->created_at?->toDayDateTimeString() ?? '—'),
                        Placeholder::make('waiting_for')
                            ->label(__('admin_ui.l.waiting_for'))
                            ->visible(fn ($record) => $record?->status === ConsultationStatus::PENDING)
                            ->content(function ($record) {
                                $hours = $record->created_at->diffInHours(now());
                                $text = __('admin_ui.l.waiting_duration', ['time' => $record->created_at->diffForHumans(now(), true)]);
                                $class = match (true) {
                                    $hours >= 48 => 'text-red-600 dark:text-red-400',
                                    $hours >= 24 => 'text-amber-600 dark:text-amber-400',
                                    default => 'text-green-600 dark:text-green-400',
                                };

                                return new \Illuminate\Support\HtmlString('<span class="font-semibold '.$class.'">'.e($text).'</span>');
                            }),
                        Textarea::make('question')
                            ->label(__('admin_ui.l.question'))
                            ->columnSpanFull()
                            ->rows(4)
                            ->disabled()
                            ->dehydrated(false),
                    ]),
                Section::make(__('admin_ui.l.response'))
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label(__('admin_ui.l.status'))
                            ->options(collect(ConsultationStatus::cases())->mapWithKeys(fn ($case) => [$case->value => AdminEnum::label($case)]))
                            ->required(),
                        DateTimePicker::make('scheduled_datetime')
                            ->label(__('admin_ui.l.scheduled_date_time'))
                            ->visible(fn (?\App\Modules\Consultation\Models\Consultation $record) => $record?->type === ConsultationType::PAID_BOOKING),
                        Textarea::make('answer')
                            ->label(__('admin_ui.l.answer'))
                            ->columnSpanFull()
                            ->rows(6),
                    ]),
            ]);
    }
}
