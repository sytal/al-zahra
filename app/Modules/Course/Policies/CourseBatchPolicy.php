<?php

namespace App\Modules\Course\Policies;

use App\Models\User;
use App\Modules\Course\Models\CourseBatch;

class CourseBatchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('courses.manage');
    }

    public function view(User $user, CourseBatch $courseBatch): bool
    {
        return $user->can('courses.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('courses.manage');
    }

    public function update(User $user, CourseBatch $courseBatch): bool
    {
        return $user->can('courses.manage');
    }

    public function delete(User $user, CourseBatch $courseBatch): bool
    {
        return $user->can('courses.manage');
    }
}
