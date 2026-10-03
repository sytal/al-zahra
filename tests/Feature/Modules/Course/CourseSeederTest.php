<?php

use App\Modules\Course\Models\Course;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CourseSeeder;
use Database\Seeders\DirectorSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\UserSeeder;

beforeEach(function () {
    $this->seed([RolePermissionSeeder::class, UserSeeder::class, DirectorSeeder::class, CategorySeeder::class, CourseSeeder::class]);
});

it('gives every demo course at least one module with a non-uniform block-type mix', function () {
    $courses = Course::with('modules.blocks')->get();

    expect($courses)->toHaveCount(3);

    $typeSetsPerCourse = [];

    foreach ($courses as $course) {
        expect($course->modules()->count())->toBeGreaterThanOrEqual(1);

        $types = $course->modules->flatMap(fn ($module) => $module->blocks->pluck('type'))->unique()->sort()->values()->all();

        expect($types)->not->toBeEmpty();

        $typeSetsPerCourse[] = $types;
    }

    // Prove the block-type mix differs across courses (no two courses share the exact same set).
    $unique = collect($typeSetsPerCourse)->map(fn ($set) => implode(',', $set))->unique();
    expect($unique)->toHaveCount(3);
});

it('seeds a batch with a small seat count for the waitlist path', function () {
    $batched = Course::has('batches')->first();

    expect($batched)->not->toBeNull();
    expect($batched->batches()->first()->seats)->toBe(2);
});

it('is idempotent when run twice', function () {
    $this->seed(CourseSeeder::class);

    expect(Course::count())->toBe(3);
});
