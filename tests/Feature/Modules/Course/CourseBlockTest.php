<?php

use App\Modules\Course\Models\CourseBlock;
use App\Modules\Course\Models\CourseModule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

it('exposes an admin-uploaded assignment attachment via the attachments media collection', function () {
    Storage::fake('public');

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
        'content' => ['instructions' => 'Do the task'],
    ]);

    $path = UploadedFile::fake()->create('task-brief.pdf', 10)->store('uploads/courses/assignments', 'public');

    // Same addMediaFromDisk() call ModulesRelationManager::syncAssignmentAttachments()
    // performs after the admin form saves the block.
    $block->addMediaFromDisk($path, 'public')
        ->usingName('task-brief')
        ->usingFileName('task-brief.pdf')
        ->toMediaCollection('attachments');

    expect($block->getMedia('attachments'))->toHaveCount(1);
    expect($block->getFirstMedia('attachments')->file_name)->toBe('task-brief.pdf');
});
