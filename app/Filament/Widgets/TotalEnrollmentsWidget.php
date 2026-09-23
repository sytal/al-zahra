<?php

namespace App\Filament\Widgets;

use App\Modules\Course\Models\Enrollment;
use App\Support\Enums\EnrollmentStatus;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalEnrollmentsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Enrollments', Enrollment::query()->count())
                ->description(Enrollment::query()->where('status', EnrollmentStatus::COMPLETED)->count().' completed')
                ->icon('heroicon-o-user-group')
                ->color('info'),
        ];
    }
}
