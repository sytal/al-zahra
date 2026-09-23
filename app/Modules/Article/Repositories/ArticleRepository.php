<?php

namespace App\Modules\Article\Repositories;

use App\Modules\Article\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function paginatePublished(?int $categoryId = null, ?string $search = null, int $perPage = 12): LengthAwarePaginator
    {
        return Article::query()
            ->with(['author', 'category', 'tags'])
            ->where('is_published', true)
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($search, fn ($query) => $query->whereJsonContains('title->en', $search))
            ->latest('published_at')
            ->paginate($perPage);
    }

    public function findPublishedBySlug(string $slug): ?Article
    {
        return Article::query()
            ->with(['author', 'category', 'tags'])
            ->where('is_published', true)
            ->where('slug', $slug)
            ->first();
    }

    public function relatedTo(Article $article, int $limit = 3): iterable
    {
        return Article::query()
            ->with(['category'])
            ->where('is_published', true)
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }
}
