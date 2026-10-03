<?php

use App\Modules\Course\Models\Enrollment;
use App\Modules\Course\Models\LessonProgress;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CourseSeeder;
use Database\Seeders\DirectorSeeder;
use Database\Seeders\EnrollmentSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\UserSeeder;

beforeEach(function () {
    $this->seed([RolePermissionSeeder::class, UserSeeder::class, DirectorSeeder::class, CategorySeeder::class, CourseSeeder::class]);
});

it('enrolls the demo student in two courses with consistent progress', function () {
    $this->seed(EnrollmentSeeder::class);

    $student = App\Models\User::where('email', 'student@alzahra.institute')->first();
    expect(Enrollment::where('user_id', $student->id)->count())->toBe(2);
    expect(LessonProgress::count())->toBe(1);

    $started = Enrollment::has('lessonProgress')->firstOrFail();
    $total = $started->course->lessons()->count();

    expect($started->progress_percent)->toBe((int) round(1 / $total * 100));
});

it('is idempotent when run twice', function () {
    $this->seed(EnrollmentSeeder::class);
    $this->seed(EnrollmentSeeder::class);

    $student = App\Models\User::where('email', 'student@alzahra.institute')->first();
    expect(Enrollment::where('user_id', $student->id)->count())->toBe(2);
    expect(LessonProgress::count())->toBe(1);
});
