<?php

namespace App\Modules\Support\Notifications;

use App\Modules\Support\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to Director/Admin users (contact.manage permission) when a student
 * opens a new support ticket (docs/COURSE-BUILDER-DEV-PLAN.md Phase 7).
 */
class NewSupportTicketNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public SupportTicket $ticket,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('notifications_ui.new_ticket_subject', ['subject' => $this->ticket->subject]))
            ->greeting(__('notifications_ui.greeting', ['name' => $notifiable->name]))
            ->line(__('notifications_ui.new_ticket_body', ['subject' => $this->ticket->subject]))
            ->action(__('notifications_ui.view_ticket'), route('filament.admin.resources.support-tickets.edit', $this->ticket));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => __('notifications_ui.new_ticket_subject', ['subject' => $this->ticket->subject]),
            'message' => __('notifications_ui.new_ticket_body', ['subject' => $this->ticket->subject]),
            'url' => route('filament.admin.resources.support-tickets.edit', $this->ticket),
        ];
    }
}
