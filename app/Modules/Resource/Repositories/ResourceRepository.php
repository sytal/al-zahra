<?php

namespace App\Modules\Resource\Repositories;

use App\Modules\Resource\Models\Resource;
use App\Support\CacheService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ResourceRepository implements ResourceRepositoryInterface
{
    public function __construct(protected CacheService $cache) {}

    public function paginatePublished(?int $categoryId = null, ?string $search = null, int $perPage = 12, ?string $type = null, ?string $pricing = null): LengthAwarePaginator
    {
        $page = (int) request()->get('page', 1);
        $key = 'resources:list:'.md5(json_encode([$categoryId, $search, $perPage, $page, $type, $pricing, app()->getLocale()]));

        return $this->cache->remember($key, CacheService::LIST_TTL, fn () => Resource::query()
            ->with(['category'])
            ->where('is_published', true)
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($type, fn ($query) => $query->where('resource_type', $type))
            ->when($pricing === 'free', fn ($query) => $query->where('is_free', true))
            ->when($pricing === 'paid', fn ($query) => $query->where('is_free', false))
            ->when($search, fn ($query) => $query->where(fn ($q) => $q->where('title->'.app()->getLocale(), 'like', '%'.addcslashes($search, '%_\\').'%')->orWhere('title->en', 'like', '%'.addcslashes($search, '%_\\').'%')))
            ->latest()
            ->paginate($perPage));
    }

    public function listStats(): array
    {
        return $this->cache->remember('resources:stats:list', CacheService::LIST_TTL, function () {
            $published = Resource::query()->where('is_published', true);

            return [
                'total' => (clone $published)->count(),
                'free' => (clone $published)->where('is_free', true)->count(),
                'categories' => (clone $published)->whereNotNull('category_id')
                    ->selectRaw('category_id, count(*) as aggregate')->groupBy('category_id')->pluck('aggregate', 'category_id')->all(),
                'types' => (clone $published)->selectRaw('resource_type, count(*) as aggregate')->groupBy('resource_type')->pluck('aggregate', 'resource_type')->all(),
            ];
        });
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
