<?php

namespace App\Modules\Article\Repositories;

use App\Modules\Article\Models\Article;
use App\Support\CacheService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function __construct(protected CacheService $cache) {}

    public function paginatePublished(?int $categoryId = null, ?string $search = null, int $perPage = 12): LengthAwarePaginator
    {
        $page = (int) request()->get('page', 1);
        $key = 'articles:list:'.md5(json_encode([$categoryId, $search, $perPage, $page]));

        return $this->cache->remember($key, CacheService::LIST_TTL, fn () => Article::query()
            ->with(['author', 'category', 'tags'])
            ->where('is_published', true)
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($search, fn ($query) => $query->whereJsonContains('title->en', $search))
            ->latest('published_at')
            ->paginate($perPage));
    }

    public function findPublishedBySlug(string $slug): ?Article
    {
        return $this->cache->remember("articles:slug:{$slug}", CacheService::DETAIL_TTL, fn () => Article::query()
            ->with(['author', 'category', 'tags'])
            ->where('is_published', true)
            ->where('slug', $slug)
            ->first());
    }

    public function relatedTo(Article $article, int $limit = 3): iterable
    {
        $key = "articles:related:{$article->id}:{$limit}";

        return $this->cache->remember($key, CacheService::LIST_TTL, fn () => Article::query()
            ->with(['category'])
            ->where('is_published', true)
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->latest('published_at')
            ->limit($limit)
            ->get());
    }
}
