<?php

namespace App\Modules\Newsletter\Jobs;

use App\Modules\Newsletter\Mail\NewsletterConfirmationMail;
use App\Modules\Newsletter\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewsletterConfirmationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public NewsletterSubscriber $subscriber,
    ) {
        $this->onQueue('emails');
    }

    public function handle(): void
    {
        Mail::to($this->subscriber->email)->send(new NewsletterConfirmationMail($this->subscriber));
    }
}
