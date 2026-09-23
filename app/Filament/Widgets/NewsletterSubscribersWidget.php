<?php

namespace App\Filament\Widgets;

use App\Modules\Newsletter\Models\NewsletterSubscriber;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NewsletterSubscribersWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Newsletter Subscribers', NewsletterSubscriber::query()->count())
                ->description(NewsletterSubscriber::query()->where('is_confirmed', true)->count().' confirmed')
                ->icon('heroicon-o-envelope')
                ->color('gray'),
        ];
    }
}
