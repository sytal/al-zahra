<?php

namespace App\Modules\Course\Services;

use App\Models\User;
use App\Modules\Course\Models\CourseDiscussionReply;
use App\Modules\Course\Notifications\DiscussionReplyPosted;
use Illuminate\Support\Facades\Notification;

/**
 * Admin replies to a student's discussion thread on a course block
 * (docs/COURSE-BUILDER-DEV-PLAN.md Phase 7/8 — Filament "Course discussions"
 * page). Mirrors the student-side posting in CourseLearn::postDiscussionReply.
 */
class CourseDiscussionAdminService
{
    public function reply(CourseDiscussionReply $thread, User $author, string $body): CourseDiscussionReply
    {
        $reply = CourseDiscussionReply::create([
            'course_block_id' => $thread->course_block_id,
            'user_id' => $thread->user_id,
            'author_id' => $author->id,
            'body' => $body,
        ]);

        if ($thread->user) {
            Notification::send($thread->user, new DiscussionReplyPosted($reply, postedByStudent: false));
        }

        return $reply;
    }
}
