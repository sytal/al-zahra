<?php

namespace App\Modules\Article\Repositories;

use App\Modules\Article\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ArticleRepositoryInterface
{
    public function paginatePublished(?int $categoryId = null, ?string $search = null, int $perPage = 12): LengthAwarePaginator;

    public function findPublishedBySlug(string $slug): ?Article;

    public function relatedTo(Article $article, int $limit = 3): iterable;
}
