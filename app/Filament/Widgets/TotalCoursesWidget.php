<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasSparkline;
use App\Modules\Course\Models\Course;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalCoursesWidget extends StatsOverviewWidget
{
    use HasSparkline;

    protected static ?int $sort = 2;

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
        $query = Course::query();

        return [
            Stat::make(__('admin_ui.courses'), $query->count())
                ->description(__('admin_ui.courses_desc', ['count' => (clone $query)->where('is_published', true)->count()]))
                ->descriptionIcon('heroicon-m-check-badge')
                ->icon('heroicon-o-academic-cap')
                ->chart($this->dailyCounts($query))
                ->color('success'),
        ];
    }
}
