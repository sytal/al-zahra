<?php

use App\Modules\Course\Models\CourseBatch;
use App\Modules\Course\Models\CourseBatchEnrollment;
use App\Modules\Course\Models\Enrollment;
use App\Support\Enums\EnrollmentStatus;
use Spatie\Activitylog\Models\Activity;

it('saves a batch enrollment and relates to its batch and enrollment', function () {
    $course = makeCourse();
    $batch = CourseBatch::create([
        'course_id' => $course->id,
        'label' => 'Batch 1',
        'starts_at' => now()->addWeek(),
        'seats' => 20,
    ]);
    $user = userWithRole('student');
    $enrollment = Enrollment::create([
        'user_id' => $user->id,
        'course_id' => $course->id,
        'course_batch_id' => $batch->id,
        'status' => EnrollmentStatus::ACTIVE,
        'enrolled_at' => now(),
    ]);

    $batchEnrollment = CourseBatchEnrollment::create([
        'course_batch_id' => $batch->id,
        'enrollment_id' => $enrollment->id,
        'roll_number' => 'B1-0001',
        'status' => 'enrolled',
    ]);

    expect($batchEnrollment->exists)->toBeTrue();
    expect($batchEnrollment->batch->id)->toBe($batch->id);
    expect($batchEnrollment->enrollment->id)->toBe($enrollment->id);

    expect(Activity::query()->where('subject_type', CourseBatchEnrollment::class)->where('subject_id', $batchEnrollment->id)->exists())->toBeTrue();
});
