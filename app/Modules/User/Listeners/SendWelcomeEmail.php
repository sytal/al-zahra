<?php

namespace App\Modules\User\Listeners;

use App\Modules\User\Jobs\SendWelcomeEmailJob;
use Illuminate\Auth\Events\Registered;

class SendWelcomeEmail
{
    public function handle(Registered $event): void
    {
        SendWelcomeEmailJob::dispatch($event->user);
    }
}
