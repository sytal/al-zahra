<?php

use App\Modules\Course\Livewire\CourseLearn;
use App\Modules\Course\Models\CourseModule;
use App\Modules\Course\Models\Enrollment;
use App\Support\Enums\EnrollmentStatus;
use Livewire\Livewire;

it('redirects a completed enrollment away from course blocks to the complete screen', function () {
    $course = makeCourse();
    $module = CourseModule::create([
        'course_id' => $course->id,
        'title' => ['en' => 'Module one'],
        'sort_order' => 1,
    ]);

    $user = userWithRole('student');
    Enrollment::create([
        'user_id' => $user->id,
        'course_id' => $course->id,
        'status' => EnrollmentStatus::COMPLETED,
        'enrolled_at' => now(),
        'completed_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test(CourseLearn::class, ['course' => $course, 'module' => $module])
        ->assertRedirect(route('dashboard.courses.complete', [
            'locale' => app()->getLocale(),
            'course' => $course->slug,
        ]));
});
