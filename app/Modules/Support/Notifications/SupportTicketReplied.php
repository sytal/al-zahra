<?php

namespace App\Modules\Support\Notifications;

use App\Modules\Support\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to the other party on a support ticket (student <-> admin) when a
 * reply is posted (docs/COURSE-BUILDER-DEV-PLAN.md Phase 7).
 */
class SupportTicketReplied extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public SupportTicket $ticket,
        public bool $repliedByStaff,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function url(): string
    {
        return $this->repliedByStaff
            ? route('dashboard.support.index')
            : route('filament.admin.resources.support-tickets.edit', $this->ticket);
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('notifications_ui.ticket_replied_subject', ['subject' => $this->ticket->subject]))
            ->greeting(__('notifications_ui.greeting', ['name' => $notifiable->name]))
            ->line(__('notifications_ui.ticket_replied_body', ['subject' => $this->ticket->subject]))
            ->action(__('notifications_ui.view_ticket'), $this->url());
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => __('notifications_ui.ticket_replied_subject', ['subject' => $this->ticket->subject]),
            'message' => __('notifications_ui.ticket_replied_body', ['subject' => $this->ticket->subject]),
            'url' => $this->url(),
        ];
    }
}
