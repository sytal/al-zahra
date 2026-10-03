<?php

use App\Modules\Course\Models\CourseBlock;
use App\Modules\Course\Models\CourseBlockProgress;
use App\Modules\Course\Models\CourseModule;
use App\Modules\Course\Models\Enrollment;
use App\Modules\Course\Notifications\AssignmentMarked;
use App\Modules\Course\Services\AssignmentReviewService;
use App\Support\Enums\EnrollmentStatus;
use Illuminate\Support\Facades\Notification;

it('marks an assignment submission and notifies the student', function () {
    Notification::fake();

    $course = makeCourse();
    $module = CourseModule::create([
        'course_id' => $course->id,
        'title' => ['en' => 'Module one'],
        'sort_order' => 1,
    ]);
    $block = CourseBlock::create([
        'course_module_id' => $module->id,
        'type' => 'assignment',
        'title' => ['en' => 'Assignment block'],
        'sort_order' => 1,
    ]);
    $student = userWithRole('student');
    $enrollment = Enrollment::create([
        'user_id' => $student->id,
        'course_id' => $course->id,
        'status' => EnrollmentStatus::ACTIVE,
        'enrolled_at' => now(),
    ]);
    $progress = CourseBlockProgress::create([
        'enrollment_id' => $enrollment->id,
        'course_block_id' => $block->id,
        'status' => 'unlocked',
        'data' => ['mark' => 'submitted'],
    ]);

    app(AssignmentReviewService::class)->mark($progress, 'pass', 'Great work');

    expect($progress->refresh()->data)->toMatchArray(['mark' => 'pass', 'comment' => 'Great work']);

    Notification::assertSentTo(
        $student,
        AssignmentMarked::class,
        fn ($notification) => $notification->mark === 'pass'
    );
});
