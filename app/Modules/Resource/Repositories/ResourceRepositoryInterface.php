<?php

namespace App\Modules\Resource\Repositories;

use App\Modules\Resource\Models\Resource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ResourceRepositoryInterface
{
    public function paginatePublished(?int $categoryId = null, ?string $search = null, int $perPage = 12, ?string $type = null, ?string $pricing = null): LengthAwarePaginator;

    public function listStats(): array;

    public function findPublishedBySlug(string $slug): ?Resource;

    public function relatedTo(Resource $resource, int $limit = 3): iterable;
}
