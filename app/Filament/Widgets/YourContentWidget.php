<?php

namespace App\Filament\Widgets;

use App\Modules\Article\Models\Article;
use App\Modules\Research\Models\ResearchPaper;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class YourContentWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        $user = auth()->user();

        return $user
            && $user->can('articles.create')
            && ! $user->can('settings.manage');
    }

    protected function getStats(): array
    {
        $articles = Article::where('author_id', auth()->id());
        $research = ResearchPaper::where('author_id', auth()->id());

        return [
            Stat::make(__('admin_ui.your_articles'), $articles->count())
                ->description(__('admin_ui.your_articles_desc', [
                    'published' => (clone $articles)->where('is_published', true)->count(),
                    'draft' => (clone $articles)->where('is_published', false)->count(),
                ]))
                ->descriptionIcon('heroicon-m-pencil-square')
                ->icon('heroicon-o-newspaper')
                ->color('success'),
            Stat::make(__('admin_ui.your_research'), $research->count())
                ->description(__('admin_ui.your_research_desc', [
                    'published' => (clone $research)->where('is_published', true)->count(),
                    'draft' => (clone $research)->where('is_published', false)->count(),
                ]))
                ->descriptionIcon('heroicon-m-pencil-square')
                ->icon('heroicon-o-document-magnifying-glass')
                ->color('info'),
        ];
    }
}
