<?php

namespace App\Modules\Course\Policies;

use App\Models\User;
use App\Modules\Course\Models\CourseModule;

class CourseModulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('courses.manage');
    }

    public function view(User $user, CourseModule $courseModule): bool
    {
        return $user->can('courses.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('courses.manage');
    }

    public function update(User $user, CourseModule $courseModule): bool
    {
        return $user->can('courses.manage');
    }

    public function delete(User $user, CourseModule $courseModule): bool
    {
        return $user->can('courses.manage');
    }
}
