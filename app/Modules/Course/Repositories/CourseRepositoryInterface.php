<?php

namespace App\Modules\Course\Repositories;

use App\Modules\Course\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CourseRepositoryInterface
{
    public function paginatePublished(?string $audience = null, ?string $level = null, ?bool $isFree = null, ?string $search = null, int $perPage = 12): LengthAwarePaginator;

    public function findPublishedBySlug(string $slug): ?Course;

    public function relatedTo(Course $course, int $limit = 3): iterable;
}
