<?php

namespace App\Filament\Widgets;

use App\Modules\Article\Models\Article;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalArticlesWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Articles', Article::query()->count())
                ->description(Article::query()->where('is_published', true)->count().' published')
                ->icon('heroicon-o-newspaper')
                ->color('primary'),
        ];
    }
}
