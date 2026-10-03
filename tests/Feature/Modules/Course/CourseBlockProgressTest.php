<?php

use App\Modules\Course\Models\CourseBlock;
use App\Modules\Course\Models\CourseBlockProgress;
use App\Modules\Course\Models\CourseModule;
use App\Modules\Course\Models\Enrollment;
use App\Support\Enums\EnrollmentStatus;
use Spatie\Activitylog\Models\Activity;

it('saves progress and is retrievable via CourseBlock::progressFor', function () {
    $course = makeCourse();
    $module = CourseModule::create([
        'course_id' => $course->id,
        'title' => ['en' => 'Module one'],
        'sort_order' => 1,
    ]);
    $block = CourseBlock::create([
        'course_module_id' => $module->id,
        'type' => 'reading',
        'title' => ['en' => 'Reading block'],
        'sort_order' => 1,
    ]);
    $user = userWithRole('student');
    $enrollment = Enrollment::create([
        'user_id' => $user->id,
        'course_id' => $course->id,
        'status' => EnrollmentStatus::ACTIVE,
        'enrolled_at' => now(),
    ]);

    $progress = CourseBlockProgress::create([
        'enrollment_id' => $enrollment->id,
        'course_block_id' => $block->id,
        'status' => 'done',
        'completed_at' => now(),
    ]);

    expect($progress->exists)->toBeTrue();
    expect($block->progressFor($enrollment)->id)->toBe($progress->id);

    expect(Activity::query()->where('subject_type', CourseBlockProgress::class)->where('subject_id', $progress->id)->exists())->toBeTrue();
});
