<?php

namespace App\Modules\Course\Repositories;

use App\Modules\Course\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CourseRepository implements CourseRepositoryInterface
{
    public function paginatePublished(?string $audience = null, ?string $level = null, ?bool $isFree = null, ?string $search = null, int $perPage = 12): LengthAwarePaginator
    {
        return Course::query()
            ->with(['category', 'instructor'])
            ->where('is_published', true)
            ->when($audience, fn ($query) => $query->where('audience', $audience))
            ->when($level, fn ($query) => $query->where('level', $level))
            ->when(! is_null($isFree), fn ($query) => $query->where('is_free', $isFree))
            ->when($search, fn ($query) => $query->whereJsonContains('title->en', $search))
            ->latest()
            ->paginate($perPage);
    }

    public function findPublishedBySlug(string $slug): ?Course
    {
        return Course::query()
            ->with(['category', 'instructor', 'lessons' => fn ($query) => $query->orderBy('sort_order')])
            ->where('is_published', true)
            ->where('slug', $slug)
            ->first();
    }

    public function relatedTo(Course $course, int $limit = 3): iterable
    {
        return Course::query()
            ->where('is_published', true)
            ->where('id', '!=', $course->id)
            ->where('category_id', $course->category_id)
            ->limit($limit)
            ->get();
    }
}
