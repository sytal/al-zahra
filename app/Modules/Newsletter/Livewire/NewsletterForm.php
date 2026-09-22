<?php

namespace App\Modules\Newsletter\Livewire;

use App\Modules\Newsletter\Models\NewsletterSubscriber;
use Illuminate\Support\Str;
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

        NewsletterSubscriber::firstOrCreate(
            ['email' => $this->email],
            ['locale' => app()->getLocale()]
        );

        $this->subscribed = true;
        $this->email = '';
    }

    public function render()
    {
        return view('livewire.newsletter.newsletter-form');
    }
}
