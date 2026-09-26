<?php

namespace App\Filament\Widgets\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasSparkline
{
    /**
     * @return array<int, int>
     */
    protected function dailyCounts(Builder $query, int $days = 7): array
    {
        $start = now()->subDays($days - 1)->startOfDay();

        $counts = (clone $query)
            ->where('created_at', '>=', $start)
            ->pluck('created_at')
            ->countBy(fn ($date) => $date->format('Y-m-d'));

        return collect(range(0, $days - 1))
            ->map(fn (int $i) => (int) ($counts[$start->copy()->addDays($i)->format('Y-m-d')] ?? 0))
            ->all();
    }
}
