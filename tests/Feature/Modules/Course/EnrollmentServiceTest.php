<?php

use App\Modules\Course\Exceptions\EnrollmentCapExceededException;
use App\Modules\Course\Models\Enrollment;
use App\Modules\Course\Notifications\EnrollmentStatusChanged;
use App\Modules\Course\Services\EnrollmentService;
use App\Support\Enums\EnrollmentStatus;
use Illuminate\Support\Facades\Notification;

it('notifies the student with an enrolled status when enrolling', function () {
    Notification::fake();

    $user = userWithRole('student');
    $course = makeCourse();

    $service = app(EnrollmentService::class);
    $service->enroll($user, $course);

    Notification::assertSentTo(
        $user,
        EnrollmentStatusChanged::class,
        fn ($notification) => $notification->status === 'enrolled'
    );
});

it('blocks a 3rd active enrollment with the exact cap message', function () {
    $user = userWithRole('student');
    $courseOne = makeCourse();
    $courseTwo = makeCourse();
    $courseThree = makeCourse();

    $service = app(EnrollmentService::class);
    $service->enroll($user, $courseOne);
    $service->enroll($user, $courseTwo);

    expect(Enrollment::where('user_id', $user->id)->count())->toBe(2);

    try {
        $service->enroll($user, $courseThree);
        $this->fail('Expected EnrollmentCapExceededException to be thrown.');
    } catch (EnrollmentCapExceededException $e) {
        expect($e->getMessage())->toBe(
            'You can be enrolled in up to 2 courses at a time. Finish one of your current courses to enroll in a new one.'
        );
        expect($e->activeEnrollments)->toHaveCount(2);
    }

    expect(Enrollment::where('user_id', $user->id)->where('course_id', $courseThree->id)->exists())->toBeFalse();
});

it('frees a slot when leaving a course, allowing a new enrollment', function () {
    $user = userWithRole('student');
    $courseOne = makeCourse();
    $courseTwo = makeCourse();
    $courseThree = makeCourse();

    $service = app(EnrollmentService::class);
    $enrollmentOne = $service->enroll($user, $courseOne);
    $service->enroll($user, $courseTwo);

    expect(Enrollment::activeCourseCount($user))->toBe(2);

    $service->leave($enrollmentOne);
    $enrollmentOne->refresh();

    expect($enrollmentOne->left_at)->not->toBeNull();
    expect($enrollmentOne->status)->toBe(EnrollmentStatus::ACTIVE);
    expect(Enrollment::activeCourseCount($user))->toBe(1);

    $enrollmentThree = $service->enroll($user, $courseThree);

    expect($enrollmentThree->exists)->toBeTrue();
    expect(Enrollment::activeCourseCount($user))->toBe(2);
});
