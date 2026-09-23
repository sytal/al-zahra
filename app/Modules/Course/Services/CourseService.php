<?php

namespace App\Modules\Course\Services;

use App\Models\User;
use App\Modules\Course\Events\CourseCompleted;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Models\CourseLesson;
use App\Modules\Course\Models\Enrollment;
use App\Modules\Course\Models\LessonProgress;
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

    /**
     * Marks a lesson complete for the user's enrollment in the lesson's
     * course, recomputes Enrollment.progress_percent, and fires
     * CourseCompleted once progress reaches 100% (Part F).
     */
    public function markLessonComplete(User $user, CourseLesson $lesson): Enrollment
    {
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $lesson->course_id)
            ->firstOrFail();

        LessonProgress::firstOrCreate(
            ['enrollment_id' => $enrollment->id, 'course_lesson_id' => $lesson->id],
            ['completed_at' => now()]
        );

        $totalLessons = CourseLesson::where('course_id', $lesson->course_id)->count();
        $completedLessons = LessonProgress::where('enrollment_id', $enrollment->id)->count();

        $enrollment->progress_percent = $totalLessons > 0
            ? (int) round($completedLessons / $totalLessons * 100)
            : 0;

        $justCompleted = $enrollment->progress_percent >= 100
            && $enrollment->status !== EnrollmentStatus::COMPLETED;

        if ($justCompleted) {
            $enrollment->status = EnrollmentStatus::COMPLETED;
            $enrollment->completed_at = now();
        }

        $enrollment->save();

        if ($justCompleted) {
            event(new CourseCompleted($enrollment));
        }

        return $enrollment;
    }
}
