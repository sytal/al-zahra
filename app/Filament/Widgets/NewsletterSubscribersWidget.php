<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasSparkline;
use App\Modules\Newsletter\Models\NewsletterSubscriber;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NewsletterSubscribersWidget extends StatsOverviewWidget
{
    use HasSparkline;

    protected static ?int $sort = 5;

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
        $query = NewsletterSubscriber::query();

        return [
            Stat::make(__('admin_ui.subscribers'), $query->count())
                ->description(__('admin_ui.subscribers_desc', ['count' => (clone $query)->where('is_confirmed', true)->count()]))
                ->descriptionIcon('heroicon-m-check-circle')
                ->icon('heroicon-o-envelope')
                ->chart($this->dailyCounts($query))
                ->color('gray'),
        ];
    }
}
