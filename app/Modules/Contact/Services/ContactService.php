<?php

namespace App\Modules\Contact\Services;

use App\Modules\Contact\Jobs\SendContactEmailJob;
use App\Modules\Contact\Models\ContactMessage;
use App\Support\Enums\ContactMessageStatus;

class ContactService
{
    public function submit(array $data): ContactMessage
    {
        $message = ContactMessage::create([
            ...$data,
            'status' => ContactMessageStatus::NEW,
        ]);

        SendContactEmailJob::dispatch($message);

        return $message;
    }
}
