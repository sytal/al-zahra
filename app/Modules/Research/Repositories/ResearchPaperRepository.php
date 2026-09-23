<?php

namespace App\Modules\Research\Repositories;

use App\Modules\Research\Models\ResearchPaper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ResearchPaperRepository implements ResearchPaperRepositoryInterface
{
    public function paginatePublished(?int $categoryId = null, ?string $search = null, int $perPage = 12): LengthAwarePaginator
    {
        return ResearchPaper::query()
            ->with(['author', 'category'])
            ->where('is_published', true)
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($search, fn ($query) => $query->whereJsonContains('title->en', $search))
            ->latest('published_year')
            ->paginate($perPage);
    }

    public function findPublishedBySlug(string $slug): ?ResearchPaper
    {
        return ResearchPaper::query()
            ->with(['author', 'category'])
            ->where('is_published', true)
            ->where('slug', $slug)
            ->first();
    }

    public function relatedTo(ResearchPaper $paper, int $limit = 3): iterable
    {
        return ResearchPaper::query()
            ->where('is_published', true)
            ->where('id', '!=', $paper->id)
            ->where('category_id', $paper->category_id)
            ->limit($limit)
            ->get();
    }
}
