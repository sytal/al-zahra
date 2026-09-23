<?php

namespace App\Modules\Consultation\Services;

use App\Modules\Consultation\Jobs\SendConsultationReceivedEmailJob;
use App\Modules\Consultation\Models\Consultation;
use App\Support\Enums\ConsultationStatus;
use Illuminate\Support\Facades\Auth;

class ConsultationService
{
    public function submit(array $data): Consultation
    {
        $consultation = Consultation::create([
            ...$data,
            'user_id' => Auth::id(),
            'status' => ConsultationStatus::PENDING,
        ]);

        if ($consultation->guest_email || $consultation->user?->email) {
            SendConsultationReceivedEmailJob::dispatch($consultation);
        }

        return $consultation;
    }
}
