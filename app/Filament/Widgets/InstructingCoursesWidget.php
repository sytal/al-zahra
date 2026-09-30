<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasSparkline;
use App\Modules\Course\Models\Course;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InstructingCoursesWidget extends StatsOverviewWidget
{
    use HasSparkline;

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = ['default' => 'full', 'md' => 1];

    protected function getColumns(): int
    {
        return 1;
    }

    public static function canView(): bool
    {
        return Course::where('instructor_id', auth()->id())->exists();
    }

    protected function getStats(): array
    {
        $query = Course::where('instructor_id', auth()->id());

        return [
            Stat::make(__('admin_ui.instructing'), $query->count())
                ->description(__('admin_ui.instructing_desc', ['count' => (clone $query)->sum('enrolled_count')]))
                ->descriptionIcon('heroicon-m-user-group')
                ->icon('heroicon-o-presentation-chart-line')
                ->chart($this->dailyCounts($query))
                ->color('warning'),
        ];
    }
}
