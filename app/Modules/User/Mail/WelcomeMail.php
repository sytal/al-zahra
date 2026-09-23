<?php

namespace App\Modules\User\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
    ) {}

    public function build(): self
    {
        return $this->subject(__('user.welcome_mail_subject', ['app_name' => config('app.name')]))
            ->markdown('emails.user.welcome', [
                'user' => $this->user,
            ]);
    }
}
