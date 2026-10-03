<?php

use App\Modules\Course\Models\CourseBlock;
use App\Modules\Course\Models\CourseDiscussionReply;
use App\Modules\Course\Models\CourseModule;
use Spatie\Activitylog\Models\Activity;

it('saves a discussion reply and relates to its block', function () {
    $course = makeCourse();
    $module = CourseModule::create([
        'course_id' => $course->id,
        'title' => ['en' => 'Module one'],
        'sort_order' => 1,
    ]);
    $block = CourseBlock::create([
        'course_module_id' => $module->id,
        'type' => 'discussion',
        'title' => ['en' => 'Discussion block'],
        'sort_order' => 1,
    ]);
    $student = userWithRole('student');

    $reply = CourseDiscussionReply::create([
        'course_block_id' => $block->id,
        'user_id' => $student->id,
        'author_id' => $student->id,
        'body' => 'My reply',
    ]);

    expect($reply->exists)->toBeTrue();
    expect($reply->block->id)->toBe($block->id);
    expect($block->discussionReplies()->first()->id)->toBe($reply->id);

    expect(Activity::query()->where('subject_type', CourseDiscussionReply::class)->where('subject_id', $reply->id)->exists())->toBeTrue();
});
