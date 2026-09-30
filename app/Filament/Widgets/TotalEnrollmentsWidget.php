<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasSparkline;
use App\Modules\Course\Models\Enrollment;
use App\Support\Enums\EnrollmentStatus;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalEnrollmentsWidget extends StatsOverviewWidget
{
    use HasSparkline;

    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return auth()->user()?->can('settings.manage') ?? false;
    }

    protected int|string|array $columnSpan = ['default' => 'full', 'md' => 1];

    protected function getColumns(): int
    {
        return 1;
    }

    protected function getStats(): array
    {
        $query = Enrollment::query();

        return [
            Stat::make(__('admin_ui.enrollments'), $query->count())
                ->description(__('admin_ui.enrollments_desc', ['count' => (clone $query)->where('status', EnrollmentStatus::COMPLETED)->count()]))
                ->descriptionIcon('heroicon-m-check-circle')
                ->icon('heroicon-o-user-group')
                ->chart($this->dailyCounts($query))
                ->color('info'),
        ];
    }
}
