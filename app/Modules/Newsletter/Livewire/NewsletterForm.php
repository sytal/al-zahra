<?php

namespace App\Modules\Newsletter\Livewire;

use App\Modules\Newsletter\Jobs\SendNewsletterConfirmationEmailJob;
use App\Modules\Newsletter\Models\NewsletterSubscriber;
use Livewire\Component;

class NewsletterForm extends Component
{
    public string $email = '';

    public bool $subscribed = false;

    public function subscribe(): void
    {
        $this->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $this->email],
            ['locale' => app()->getLocale()]
        );

        if ($subscriber->wasRecentlyCreated) {
            SendNewsletterConfirmationEmailJob::dispatch($subscriber);
        }

        $this->subscribed = true;
        $this->email = '';
    }

    public function render()
    {
        return view('livewire.newsletter.newsletter-form');
    }
}
