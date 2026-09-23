<?php

namespace App\Modules\Consultation\Mail;

use App\Modules\Consultation\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class ConsultationAnsweredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Consultation $consultation,
    ) {}

    public function build(): self
    {
        $signedUrl = URL::temporarySignedRoute(
            'consultations.signed-view',
            now()->addHours(24),
            ['locale' => $this->consultation->user?->preferred_locale ?? app()->getLocale(), 'consultation' => $this->consultation->uuid]
        );

        return $this->subject(__('consultation.answered_mail_subject'))
            ->markdown('emails.consultation.answered', [
                'consultation' => $this->consultation,
                'signedUrl' => $signedUrl,
            ]);
    }
}
