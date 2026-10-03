<?php

namespace App\Modules\Course\Policies;

use App\Models\User;
use App\Modules\Course\Models\CourseBlock;

class CourseBlockPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('courses.manage');
    }

    public function view(User $user, CourseBlock $courseBlock): bool
    {
        return $user->can('courses.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('courses.manage');
    }

    public function update(User $user, CourseBlock $courseBlock): bool
    {
        return $user->can('courses.manage');
    }

    public function delete(User $user, CourseBlock $courseBlock): bool
    {
        return $user->can('courses.manage');
    }
}
