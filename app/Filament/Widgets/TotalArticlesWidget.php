<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasSparkline;
use App\Modules\Article\Models\Article;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalArticlesWidget extends StatsOverviewWidget
{
    use HasSparkline;

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = ['default' => 'full', 'md' => 1];

    protected function getColumns(): int
    {
        return 1;
    }

    protected function getStats(): array
    {
        $query = Article::query();

        return [
            Stat::make(__('admin_ui.articles'), $query->count())
                ->description(__('admin_ui.articles_desc', ['count' => (clone $query)->where('is_published', true)->count()]))
                ->descriptionIcon('heroicon-m-check-badge')
                ->icon('heroicon-o-newspaper')
                ->chart($this->dailyCounts($query))
                ->color('primary'),
        ];
    }
}
