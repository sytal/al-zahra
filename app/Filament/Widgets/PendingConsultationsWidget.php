<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Consultations\ConsultationResource;
use App\Modules\Consultation\Models\Consultation;
use App\Support\Enums\ConsultationStatus;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PendingConsultationsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pending Consultations', Consultation::query()->where('status', ConsultationStatus::PENDING)->count())
                ->description('Awaiting a response')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('warning')
                ->url(ConsultationResource::getUrl('index', [
                    'tableFilters' => ['status' => ['value' => ConsultationStatus::PENDING->value]],
                ])),
        ];
    }
}
