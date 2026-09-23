<?php

namespace App\Modules\Research\Repositories;

use App\Modules\Research\Models\ResearchPaper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ResearchPaperRepositoryInterface
{
    public function paginatePublished(?int $categoryId = null, ?string $search = null, int $perPage = 12): LengthAwarePaginator;

    public function findPublishedBySlug(string $slug): ?ResearchPaper;

    public function relatedTo(ResearchPaper $paper, int $limit = 3): iterable;
}
