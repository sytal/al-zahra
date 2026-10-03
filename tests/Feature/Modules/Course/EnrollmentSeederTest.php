<?php

use App\Modules\Course\Models\CourseBlockProgress;
use App\Modules\Course\Models\Enrollment;
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

    // One of the two enrollments may already be fully completed via
    // CourseSeeder's seedDemoCompletion(); the other is the one
    // EnrollmentSeeder marks in-progress with exactly one block done.
    $started = Enrollment::where('user_id', $student->id)->withCount('blockProgress')->get()->first(fn ($e) => $e->block_progress_count === 1);
    expect($started)->not->toBeNull();
    $total = $started->course->modules()->withCount('blocks')->get()->sum('blocks_count');

    expect($started->progress_percent)->toBe((int) round(1 / $total * 100));
});

it('is idempotent when run twice', function () {
    $this->seed(EnrollmentSeeder::class);
    $this->seed(EnrollmentSeeder::class);

    $student = App\Models\User::where('email', 'student@alzahra.institute')->first();
    expect(Enrollment::where('user_id', $student->id)->count())->toBe(2);
    $matches = Enrollment::where('user_id', $student->id)->withCount('blockProgress')->get()->filter(fn ($e) => $e->block_progress_count === 1);
    expect($matches)->toHaveCount(1);
});
