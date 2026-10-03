<?php

namespace App\Modules\Course\Services;

use App\Models\User;
use App\Modules\Course\Exceptions\EnrollmentCapExceededException;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Models\CourseBatch;
use App\Modules\Course\Models\CourseBatchEnrollment;
use App\Modules\Course\Models\Enrollment;
use App\Modules\Course\Notifications\EnrollmentStatusChanged;
use App\Support\Enums\EnrollmentStatus;
use Illuminate\Support\Facades\Notification;

/**
 * Enrollment lifecycle for modules+blocks courses (docs/COURSE-BUILDER-PLAN.md
 * Part C point 6, Part D point 4/7, Part E point 1).
 */
class EnrollmentService
{
    /**
     * @throws EnrollmentCapExceededException when the student already has
     *         2 active (not completed, not left) enrollments.
     */
    public function enroll(User $user, Course $course, ?CourseBatch $batch = null): Enrollment
    {
        $existing = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            if ($existing->left_at !== null) {
                $existing->left_at = null;
                $existing->save();
            }

            return $existing;
        }

        if (Enrollment::activeCourseCount($user) >= 2) {
            $active = Enrollment::where('user_id', $user->id)
                ->whereNull('left_at')
                ->where('status', EnrollmentStatus::ACTIVE)
                ->with('course')
                ->get();

            throw new EnrollmentCapExceededException($active);
        }

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'course_batch_id' => $batch?->id,
            'status' => EnrollmentStatus::ACTIVE,
            'progress_percent' => 0,
            'enrolled_at' => now(),
        ]);

        $course->increment('enrolled_count');

        if ($batch) {
            $this->assignBatchSeat($enrollment, $batch);
        }

        Notification::send($user, new EnrollmentStatusChanged(
            $enrollment,
            $enrollment->batchEnrollment?->status === 'waitlisted' ? 'waitlisted' : 'enrolled',
        ));

        return $enrollment;
    }

    /**
     * Voluntary leave (Part E point 1) — frees the 2-course slot but keeps
     * all course_block_progress rows so resuming later picks up where the
     * student left off.
     */
    public function leave(Enrollment $enrollment): Enrollment
    {
        if ($enrollment->status !== EnrollmentStatus::COMPLETED) {
            $freedBatchEnrollment = $enrollment->batchEnrollment;
            $batch = ($freedBatchEnrollment && $freedBatchEnrollment->status === 'enrolled')
                ? $enrollment->batch
                : null;

            $enrollment->left_at = now();
            $enrollment->save();

            if ($batch) {
                $this->promoteFromWaitlist($batch);
            }
        }

        return $enrollment;
    }

    /**
     * Promotes the earliest waitlisted enrollment for a batch into the
     * freed seat: flips status to enrolled, assigns a roll number, clears
     * the waitlist position, and notifies the student (Phase 4/7).
     */
    public function promoteFromWaitlist(CourseBatch $batch): void
    {
        $next = $batch->batchEnrollments()
            ->where('status', 'waitlisted')
            ->orderBy('waitlist_position')
            ->first();

        if (! $next) {
            return;
        }

        $next->status = 'enrolled';
        $next->roll_number = $this->nextRollNumber($batch);
        $next->waitlist_position = null;
        $next->save();

        $enrollment = $next->enrollment;

        Notification::send($enrollment->user, new EnrollmentStatusChanged($enrollment, 'enrolled'));
    }

    protected function assignBatchSeat(Enrollment $enrollment, CourseBatch $batch): void
    {
        $takenSeats = $batch->batchEnrollments()->where('status', 'enrolled')->count();

        if ($takenSeats < $batch->seats) {
            CourseBatchEnrollment::create([
                'course_batch_id' => $batch->id,
                'enrollment_id' => $enrollment->id,
                'roll_number' => $this->nextRollNumber($batch),
                'status' => 'enrolled',
            ]);

            return;
        }

        $nextPosition = (int) $batch->batchEnrollments()->where('status', 'waitlisted')->max('waitlist_position') + 1;

        CourseBatchEnrollment::create([
            'course_batch_id' => $batch->id,
            'enrollment_id' => $enrollment->id,
            'status' => 'waitlisted',
            'waitlist_position' => $nextPosition,
        ]);
    }

    protected function nextRollNumber(CourseBatch $batch): string
    {
        $initials = strtoupper(str_replace(' ', '', preg_replace('/[^A-Za-z0-9 ]/', '', $batch->label)));
        $initials = substr($initials, 0, 4) ?: 'B';

        $sequence = $batch->batchEnrollments()->where('status', 'enrolled')->count() + 1;

        return sprintf('%s-%04d', $initials, $sequence);
    }
}
