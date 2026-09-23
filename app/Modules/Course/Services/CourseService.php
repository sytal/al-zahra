<?php

namespace App\Modules\Course\Services;

use App\Models\User;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Models\Enrollment;
use App\Support\Enums\EnrollmentStatus;

class CourseService
{
    public function enroll(User $user, Course $course): Enrollment
    {
        $enrollment = Enrollment::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            ['status' => EnrollmentStatus::ACTIVE, 'enrolled_at' => now()]
        );

        if ($enrollment->wasRecentlyCreated) {
            $course->increment('enrolled_count');
        }

        return $enrollment;
    }

    public function isEnrolled(User $user, Course $course): bool
    {
        return Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();
    }
}
