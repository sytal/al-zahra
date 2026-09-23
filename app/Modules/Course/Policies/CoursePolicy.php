<?php

namespace App\Modules\Course\Policies;

use App\Models\User;
use App\Modules\Course\Models\Course;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('courses.manage');
    }

    public function view(User $user, Course $course): bool
    {
        return $user->can('courses.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('courses.manage');
    }

    public function update(User $user, Course $course): bool
    {
        return $user->can('courses.manage');
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->can('courses.manage');
    }

    public function publish(User $user, Course $course): bool
    {
        return $user->can('courses.publish');
    }
}
