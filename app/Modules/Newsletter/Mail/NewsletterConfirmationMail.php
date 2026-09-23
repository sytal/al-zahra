<?php

namespace App\Modules\Newsletter\Mail;

use App\Modules\Newsletter\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class NewsletterConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public NewsletterSubscriber $subscriber,
    ) {}

    public function build(): self
    {
        $signedUrl = URL::temporarySignedRoute(
            'newsletter.confirm',
            now()->addHours(24),
            ['locale' => $this->subscriber->locale ?? app()->getLocale(), 'subscriber' => $this->subscriber->uuid]
        );

        return $this->subject(__('newsletter.mail_subject'))
            ->markdown('emails.newsletter.confirmation', [
                'subscriber' => $this->subscriber,
                'signedUrl' => $signedUrl,
            ]);
    }
}
