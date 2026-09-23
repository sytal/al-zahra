<?php

namespace App\Modules\Resource\Repositories;

use App\Modules\Resource\Models\Resource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ResourceRepository implements ResourceRepositoryInterface
{
    public function paginatePublished(?int $categoryId = null, ?string $search = null, int $perPage = 12): LengthAwarePaginator
    {
        return Resource::query()
            ->with(['category'])
            ->where('is_published', true)
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($search, fn ($query) => $query->whereJsonContains('title->en', $search))
            ->latest()
            ->paginate($perPage);
    }

    public function findPublishedBySlug(string $slug): ?Resource
    {
        return Resource::query()
            ->with(['category'])
            ->where('is_published', true)
            ->where('slug', $slug)
            ->first();
    }

    public function relatedTo(Resource $resource, int $limit = 3): iterable
    {
        return Resource::query()
            ->where('is_published', true)
            ->where('id', '!=', $resource->id)
            ->where('category_id', $resource->category_id)
            ->limit($limit)
            ->get();
    }
}
