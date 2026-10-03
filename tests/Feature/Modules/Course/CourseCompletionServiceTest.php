<?php

use App\Modules\Course\Models\CourseBlock;
use App\Modules\Course\Models\CourseBlockProgress;
use App\Modules\Course\Models\CourseModule;
use App\Modules\Course\Models\Enrollment;
use App\Modules\Course\Services\CourseCompletionService;
use App\Support\Enums\EnrollmentStatus;

it('finalizes an enrollment with completed_at and an averaged final_score', function () {
    $course = makeCourse();
    $module = CourseModule::create([
        'course_id' => $course->id,
        'title' => ['en' => 'Module one'],
        'sort_order' => 1,
    ]);

    $gradedBlock = CourseBlock::create([
        'course_module_id' => $module->id,
        'type' => 'graded_quiz',
        'title' => ['en' => 'Quiz'],
        'sort_order' => 1,
    ]);

    $assignmentBlock = CourseBlock::create([
        'course_module_id' => $module->id,
        'type' => 'assignment',
        'title' => ['en' => 'Assignment'],
        'sort_order' => 2,
    ]);

    $user = userWithRole('student');
    $enrollment = Enrollment::create([
        'user_id' => $user->id,
        'course_id' => $course->id,
        'status' => EnrollmentStatus::ACTIVE,
        'enrolled_at' => now(),
    ]);

    CourseBlockProgress::create([
        'enrollment_id' => $enrollment->id,
        'course_block_id' => $gradedBlock->id,
        'status' => 'done',
        'completed_at' => now(),
        'data' => ['score' => 80],
    ]);

    CourseBlockProgress::create([
        'enrollment_id' => $enrollment->id,
        'course_block_id' => $assignmentBlock->id,
        'status' => 'done',
        'completed_at' => now(),
        'data' => ['mark' => 'pass'],
    ]);

    $service = app(CourseCompletionService::class);
    $result = $service->finalize($enrollment);

    expect($result->status)->toBe(EnrollmentStatus::COMPLETED);
    expect($result->completed_at)->not->toBeNull();
    expect((float) $result->final_score)->toBe(90.0);
});
