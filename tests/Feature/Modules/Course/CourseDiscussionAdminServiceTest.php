<?php

use App\Modules\Course\Models\CourseBlock;
use App\Modules\Course\Models\CourseDiscussionReply;
use App\Modules\Course\Models\CourseModule;
use App\Modules\Course\Notifications\DiscussionReplyPosted;
use App\Modules\Course\Services\CourseDiscussionAdminService;
use Illuminate\Support\Facades\Notification;

it('posts an admin reply to a discussion thread and notifies the student', function () {
    Notification::fake();

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
    $admin = userWithRole('admin');

    $thread = CourseDiscussionReply::create([
        'course_block_id' => $block->id,
        'user_id' => $student->id,
        'author_id' => $student->id,
        'body' => 'Can someone help?',
    ]);

    $reply = app(CourseDiscussionAdminService::class)->reply($thread, $admin, 'Sure, here is the answer.');

    expect($reply->exists)->toBeTrue();
    expect($reply->user_id)->toBe($student->id);
    expect($reply->author_id)->toBe($admin->id);

    Notification::assertSentTo(
        $student,
        DiscussionReplyPosted::class,
        fn ($notification) => $notification->postedByStudent === false
    );
});
