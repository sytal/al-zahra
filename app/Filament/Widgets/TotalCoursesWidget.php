<?php

namespace App\Filament\Widgets;

use App\Modules\Course\Models\Course;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalCoursesWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Courses', Course::query()->count())
                ->description(Course::query()->where('is_published', true)->count().' published')
                ->icon('heroicon-o-academic-cap')
                ->color('success'),
        ];
    }
}
