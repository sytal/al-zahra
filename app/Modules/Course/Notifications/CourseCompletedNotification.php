<?php

namespace App\Modules\Course\Notifications;

use App\Modules\Course\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to the student once CourseCompletionService::finalize() stamps the
 * enrollment complete and the certificate has been issued
 * (docs/COURSE-BUILDER-DEV-PLAN.md Phase 7).
 */
class CourseCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Enrollment $enrollment,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $course = $this->enrollment->course;

        return (new MailMessage)
            ->subject(__('notifications_ui.course_completed_subject', ['course' => $course->title]))
            ->greeting(__('notifications_ui.greeting', ['name' => $notifiable->name]))
            ->line(__('notifications_ui.course_completed_body', ['course' => $course->title]))
            ->action(__('notifications_ui.view_certificates'), route('dashboard.certificates.index', app()->getLocale()));
    }

    public function toArray(object $notifiable): array
    {
        $course = $this->enrollment->course;

        return [
            'title' => __('notifications_ui.course_completed_subject', ['course' => $course->title]),
            'message' => __('notifications_ui.course_completed_body', ['course' => $course->title]),
            'url' => route('dashboard.certificates.index', app()->getLocale()),
        ];
    }
}
