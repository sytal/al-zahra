<?php

namespace App\Modules\Course\Notifications;

use App\Modules\Course\Models\CourseBlockProgress;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to the student when an admin marks their assignment submission
 * Pass/Needs revision (docs/COURSE-BUILDER-DEV-PLAN.md Phase 7). Not yet
 * wired to any trigger — the Filament admin side has no "mark assignment"
 * action yet (only student submission exists). Ready to dispatch once
 * that review action is built.
 */
class AssignmentMarked extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public CourseBlockProgress $progress,
        public string $mark,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function url(): string
    {
        $block = $this->progress->block;
        $course = $block?->module?->course;

        if (! $course) {
            return route('dashboard.courses.index', app()->getLocale());
        }

        return route('dashboard.courses.study', [app()->getLocale(), $course->slug]);
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subjectKey = $this->mark === 'pass'
            ? 'notifications_ui.assignment_passed_subject'
            : 'notifications_ui.assignment_needs_revision_subject';
        $bodyKey = $this->mark === 'pass'
            ? 'notifications_ui.assignment_passed_body'
            : 'notifications_ui.assignment_needs_revision_body';

        return (new MailMessage)
            ->subject(__($subjectKey))
            ->greeting(__('notifications_ui.greeting', ['name' => $notifiable->name]))
            ->line(__($bodyKey))
            ->action(__('notifications_ui.view_course'), $this->url());
    }

    public function toArray(object $notifiable): array
    {
        $subjectKey = $this->mark === 'pass'
            ? 'notifications_ui.assignment_passed_subject'
            : 'notifications_ui.assignment_needs_revision_subject';
        $bodyKey = $this->mark === 'pass'
            ? 'notifications_ui.assignment_passed_body'
            : 'notifications_ui.assignment_needs_revision_body';

        return [
            'title' => __($subjectKey),
            'message' => __($bodyKey),
            'url' => $this->url(),
        ];
    }
}
