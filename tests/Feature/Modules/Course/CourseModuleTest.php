<?php

use App\Modules\Course\Models\CourseModule;
use Spatie\Activitylog\Models\Activity;

it('saves a course module and relates to its course', function () {
    $course = makeCourse();

    $module = CourseModule::create([
        'course_id' => $course->id,
        'title' => ['en' => 'Module one'],
        'sort_order' => 1,
    ]);

    expect($module->exists)->toBeTrue();
    expect($module->course->id)->toBe($course->id);
    expect($course->modules()->first()->id)->toBe($module->id);

    expect(Activity::query()->where('subject_type', CourseModule::class)->where('subject_id', $module->id)->exists())->toBeTrue();
});
