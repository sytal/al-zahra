<?php

namespace App\Modules\Consultation\Jobs;

use App\Modules\Consultation\Mail\ConsultationReceivedMail;
use App\Modules\Consultation\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendConsultationReceivedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Consultation $consultation,
    ) {
        $this->onQueue('emails');
    }

    public function handle(): void
    {
        $recipient = $this->consultation->guest_email ?? $this->consultation->user?->email;

        if ($recipient) {
            Mail::to($recipient)->send(new ConsultationReceivedMail($this->consultation));
        }
    }
}
