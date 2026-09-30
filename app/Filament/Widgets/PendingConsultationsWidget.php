<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasSparkline;
use App\Filament\Resources\Consultations\ConsultationResource;
use App\Modules\Consultation\Models\Consultation;
use App\Support\Enums\ConsultationStatus;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PendingConsultationsWidget extends StatsOverviewWidget
{
    use HasSparkline;

    public static function canView(): bool
    {
        return auth()->user()?->can('consultations.respond') ?? false;
    }

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = ['default' => 'full', 'md' => 1];

    protected function getColumns(): int
    {
        return 1;
    }

    protected function getStats(): array
    {
        $query = Consultation::query();

        return [
            Stat::make(__('admin_ui.consultations'), (clone $query)->where('status', ConsultationStatus::PENDING)->count())
                ->description(__('admin_ui.consultations_desc'))
                ->descriptionIcon('heroicon-m-clock')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->chart($this->dailyCounts($query))
                ->color('warning')
                ->url(ConsultationResource::getUrl('index', ['tableFilters' => ['status' => ['value' => ConsultationStatus::PENDING->value]]])),
        ];
    }
}
