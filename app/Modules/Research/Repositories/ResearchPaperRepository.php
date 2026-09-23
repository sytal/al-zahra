<?php

namespace App\Modules\Research\Repositories;

use App\Modules\Research\Models\ResearchPaper;
use App\Support\CacheService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ResearchPaperRepository implements ResearchPaperRepositoryInterface
{
    public function __construct(protected CacheService $cache) {}

    public function paginatePublished(?int $categoryId = null, ?string $search = null, int $perPage = 12): LengthAwarePaginator
    {
        $page = (int) request()->get('page', 1);
        $key = 'research-papers:list:'.md5(json_encode([$categoryId, $search, $perPage, $page]));

        return $this->cache->remember($key, CacheService::LIST_TTL, fn () => ResearchPaper::query()
            ->with(['author', 'category'])
            ->where('is_published', true)
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($search, fn ($query) => $query->whereJsonContains('title->en', $search))
            ->latest('published_year')
            ->paginate($perPage));
    }

    public function findPublishedBySlug(string $slug): ?ResearchPaper
    {
        return $this->cache->remember("research-papers:slug:{$slug}", CacheService::DETAIL_TTL, fn () => ResearchPaper::query()
            ->with(['author', 'category'])
            ->where('is_published', true)
            ->where('slug', $slug)
            ->first());
    }

    public function relatedTo(ResearchPaper $paper, int $limit = 3): iterable
    {
        $key = "research-papers:related:{$paper->id}:{$limit}";

        return $this->cache->remember($key, CacheService::LIST_TTL, fn () => ResearchPaper::query()
            ->where('is_published', true)
            ->where('id', '!=', $paper->id)
            ->where('category_id', $paper->category_id)
            ->limit($limit)
            ->get());
    }
}
