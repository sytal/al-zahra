<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\ResearchPapers\ResearchPaperResource;
use App\Modules\Article\Models\Article;
use App\Modules\Research\Models\ResearchPaper;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class YourDraftsWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return YourContentWidget::canView();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('admin_ui.your_drafts'))
            ->records(function (): Collection {
                $articles = Article::where('author_id', auth()->id())
                    ->where('is_published', false)
                    ->latest('updated_at')
                    ->limit(10)
                    ->get()
                    ->map(fn (Model $m) => (object) ['id' => $m->getKey(), 'kind' => 'article', 'title' => $m->title, 'updated_at' => $m->updated_at]);

                $research = ResearchPaper::where('author_id', auth()->id())
                    ->where('is_published', false)
                    ->latest('updated_at')
                    ->limit(10)
                    ->get()
                    ->map(fn (Model $m) => (object) ['id' => $m->getKey(), 'kind' => 'research', 'title' => $m->title, 'updated_at' => $m->updated_at]);

                return $articles->concat($research)->sortByDesc('updated_at')->take(10)->values();
            })
            ->columns([
                TextColumn::make('kind')
                    ->label(__('admin_ui.l.type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state) => $state === 'article' ? __('admin_ui.articles') : __('admin_ui.research')),
                TextColumn::make('title')->label(__('admin_ui.l.title'))->searchable(false),
                TextColumn::make('updated_at')->label(__('admin_ui.l.updated_at'))->since(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label(__('admin_ui.l.edit'))
                    ->url(fn ($record) => $record->kind === 'article'
                        ? ArticleResource::getUrl('edit', ['record' => $record->id])
                        : ResearchPaperResource::getUrl('edit', ['record' => $record->id])),
            ])
            ->emptyStateHeading(__('admin_ui.your_drafts_empty'));
    }
}
