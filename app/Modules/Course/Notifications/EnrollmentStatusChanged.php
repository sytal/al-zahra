<?php

namespace App\Modules\Course\Notifications;

use App\Modules\Course\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to the student when they enroll into an active seat or land on a
 * batch waitlist (docs/COURSE-BUILDER-DEV-PLAN.md Phase 7).
 */
class EnrollmentStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Enrollment $enrollment,
        public string $status,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $course = $this->enrollment->course;
        $url = route('dashboard.courses.study', [app()->getLocale(), $course->slug]);

        $subjectKey = $this->status === 'waitlisted'
            ? 'notifications_ui.enrollment_waitlisted_subject'
            : 'notifications_ui.enrollment_enrolled_subject';
        $bodyKey = $this->status === 'waitlisted'
            ? 'notifications_ui.enrollment_waitlisted_body'
            : 'notifications_ui.enrollment_enrolled_body';

        return (new MailMessage)
            ->subject(__($subjectKey, ['course' => $course->title]))
            ->greeting(__('notifications_ui.greeting', ['name' => $notifiable->name]))
            ->line(__($bodyKey, ['course' => $course->title]))
            ->action(__('notifications_ui.view_course'), $url);
    }

    public function toArray(object $notifiable): array
    {
        $course = $this->enrollment->course;

        $titleKey = $this->status === 'waitlisted'
            ? 'notifications_ui.enrollment_waitlisted_subject'
            : 'notifications_ui.enrollment_enrolled_subject';
        $bodyKey = $this->status === 'waitlisted'
            ? 'notifications_ui.enrollment_waitlisted_body'
            : 'notifications_ui.enrollment_enrolled_body';

        return [
            'title' => __($titleKey, ['course' => $course->title]),
            'message' => __($bodyKey, ['course' => $course->title]),
            'url' => route('dashboard.courses.study', [app()->getLocale(), $course->slug]),
        ];
    }
}
