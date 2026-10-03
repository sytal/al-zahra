<?php

namespace App\Modules\Course\Exceptions;

use App\Modules\Course\Models\Enrollment;
use Exception;
use Illuminate\Database\Eloquent\Collection;

/**
 * Thrown when a student who already has 2 active (not completed, not left)
 * enrollments tries to enroll in a 3rd course (docs/COURSE-BUILDER-PLAN.md
 * Part C point 6 / Part D point 6).
 */
class EnrollmentCapExceededException extends Exception
{
    public function __construct(
        public readonly Collection $activeEnrollments,
    ) {
        parent::__construct(
            'You can be enrolled in up to 2 courses at a time. Finish one of your current courses to enroll in a new one.'
        );
    }
}
