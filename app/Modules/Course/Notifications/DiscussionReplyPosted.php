<?php

namespace App\Modules\Course\Notifications;

use App\Modules\Course\Models\CourseDiscussionReply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent when a discussion reply is posted on a course block
 * (docs/COURSE-BUILDER-DEV-PLAN.md Phase 7). When a student posts, this
 * goes to Director/Admin users with the consultations.respond permission
 * (database only). When an admin replies, this goes to the student
 * (database+mail) — that half is not yet wired since no admin-reply UI
 * exists.
 */
class DiscussionReplyPosted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public CourseDiscussionReply $reply,
        public bool $postedByStudent,
    ) {}

    public function via(object $notifiable): array
    {
        return $this->postedByStudent ? ['database'] : ['database', 'mail'];
    }

    protected function url(): string
    {
        $block = $this->reply->block;
        $course = $block?->module?->course;

        if (! $course) {
            return route('dashboard.courses.index', app()->getLocale());
        }

        return route('dashboard.courses.study', [app()->getLocale(), $course->slug]);
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('notifications_ui.discussion_reply_subject'))
            ->greeting(__('notifications_ui.greeting', ['name' => $notifiable->name]))
            ->line(__('notifications_ui.discussion_reply_body'))
            ->action(__('notifications_ui.view_course'), $this->url());
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => __('notifications_ui.discussion_reply_subject'),
            'message' => __('notifications_ui.discussion_reply_body'),
            'url' => $this->url(),
        ];
    }
}
