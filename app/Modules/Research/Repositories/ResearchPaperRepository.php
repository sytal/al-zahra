<?php

namespace App\Modules\Research\Repositories;

use App\Modules\Research\Models\ResearchPaper;
use App\Support\CacheService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ResearchPaperRepository implements ResearchPaperRepositoryInterface
{
    public function __construct(protected CacheService $cache) {}

    public function paginatePublished(?int $categoryId = null, ?string $search = null, int $perPage = 12, ?int $year = null): LengthAwarePaginator
    {
        $page = (int) request()->get('page', 1);
        $key = 'research-papers:list:'.md5(json_encode([$categoryId, $search, $perPage, $page, $year, app()->getLocale()]));

        return $this->cache->remember($key, CacheService::LIST_TTL, fn () => ResearchPaper::query()
            ->with(['author', 'category'])
            ->where('is_published', true)
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($year, fn ($query) => $query->where('published_year', $year))
            ->when($search, fn ($query) => $query->where(fn ($q) => $q->where('title->'.app()->getLocale(), 'like', '%'.addcslashes($search, '%_\\').'%')->orWhere('title->en', 'like', '%'.addcslashes($search, '%_\\').'%')))
            ->latest('published_year')
            ->paginate($perPage));
    }

    public function listStats(): array
    {
        return $this->cache->remember('research-papers:stats:list', CacheService::LIST_TTL, function () {
            $published = ResearchPaper::query()->where('is_published', true);

            return [
                'total' => (clone $published)->count(),
                'categories' => (clone $published)->whereNotNull('category_id')
                    ->selectRaw('category_id, count(*) as aggregate')->groupBy('category_id')->pluck('aggregate', 'category_id')->all(),
                'years' => (clone $published)->whereNotNull('published_year')
                    ->selectRaw('published_year, count(*) as aggregate')->groupBy('published_year')->orderByDesc('published_year')->pluck('aggregate', 'published_year')->all(),
            ];
        });
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
