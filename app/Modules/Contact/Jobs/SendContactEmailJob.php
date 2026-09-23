<?php

namespace App\Modules\Contact\Jobs;

use App\Modules\Contact\Mail\ContactMessageMail;
use App\Modules\Contact\Models\ContactMessage;
use App\Modules\Setting\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendContactEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $contactMessage,
    ) {
        $this->onQueue('emails');
    }

    public function handle(): void
    {
        $recipient = Setting::where('key', 'contact_email')->value('value') ?: config('mail.from.address');

        Mail::to($recipient)->send(new ContactMessageMail($this->contactMessage));
    }
}
