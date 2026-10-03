<?php

use App\Modules\Course\Models\CourseBlock;
use App\Modules\Course\Models\CourseModule;
use Spatie\Activitylog\Models\Activity;

it('saves a course block and relates to its module', function () {
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
        'content' => ['body' => 'Hello'],
    ]);

    expect($block->exists)->toBeTrue();
    expect($block->module->id)->toBe($module->id);
    expect($module->blocks()->first()->id)->toBe($block->id);

    expect(Activity::query()->where('subject_type', CourseBlock::class)->where('subject_id', $block->id)->exists())->toBeTrue();
});
