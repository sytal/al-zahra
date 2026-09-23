<?php

namespace App\Modules\Resource\Repositories;

use App\Modules\Resource\Models\Resource;
use App\Support\CacheService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ResourceRepository implements ResourceRepositoryInterface
{
    public function __construct(protected CacheService $cache) {}

    public function paginatePublished(?int $categoryId = null, ?string $search = null, int $perPage = 12): LengthAwarePaginator
    {
        $page = (int) request()->get('page', 1);
        $key = 'resources:list:'.md5(json_encode([$categoryId, $search, $perPage, $page]));

        return $this->cache->remember($key, CacheService::LIST_TTL, fn () => Resource::query()
            ->with(['category'])
            ->where('is_published', true)
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($search, fn ($query) => $query->whereJsonContains('title->en', $search))
            ->latest()
            ->paginate($perPage));
    }

    public function findPublishedBySlug(string $slug): ?Resource
    {
        return $this->cache->remember("resources:slug:{$slug}", CacheService::DETAIL_TTL, fn () => Resource::query()
            ->with(['category'])
            ->where('is_published', true)
            ->where('slug', $slug)
            ->first());
    }

    public function relatedTo(Resource $resource, int $limit = 3): iterable
    {
        $key = "resources:related:{$resource->id}:{$limit}";

        return $this->cache->remember($key, CacheService::LIST_TTL, fn () => Resource::query()
            ->where('is_published', true)
            ->where('id', '!=', $resource->id)
            ->where('category_id', $resource->category_id)
            ->limit($limit)
            ->get());
    }
}
