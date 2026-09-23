<?php

namespace App\Modules\Contact\Mail;

use App\Modules\Contact\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $contactMessage,
    ) {}

    public function build(): self
    {
        return $this->subject(__('contact.mail_subject', ['subject' => $this->contactMessage->subject]))
            ->markdown('emails.contact.message', [
                'contactMessage' => $this->contactMessage,
            ]);
    }
}
