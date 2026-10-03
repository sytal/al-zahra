<?php

namespace App\Modules\Course\Notifications;

use App\Modules\Course\Models\CourseBatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to enrolled students one day before their batch's starts_at
 * (docs/COURSE-BUILDER-DEV-PLAN.md Phase 7), via the daily
 * `courses:batch-reminders` scheduled command. Mail only.
 */
class BatchStartingReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public CourseBatch $batch,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $course = $this->batch->course;
        $url = route('dashboard.courses.study', [app()->getLocale(), $course->slug]);

        return (new MailMessage)
            ->subject(__('notifications_ui.batch_starting_subject', ['course' => $course->title]))
            ->greeting(__('notifications_ui.greeting', ['name' => $notifiable->name]))
            ->line(__('notifications_ui.batch_starting_body', ['course' => $course->title, 'batch' => $this->batch->label]))
            ->action(__('notifications_ui.view_course'), $url);
    }
}
