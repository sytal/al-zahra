<?php

namespace App\Modules\Consultation\Livewire;

use App\Modules\Consultation\Services\ConsultationService;
use App\Support\Enums\ConsultationType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.public')]
#[Title('Consultation')]
class ConsultationForm extends Component
{
    public string $type = 'free_question';

    public string $guest_name = '';

    public string $guest_email = '';

    public string $topic = '';

    public string $question = '';

    public string $preferred_datetime = '';

    public bool $submitted = false;

    protected function rules(): array
    {
        $rules = [
            'type' => ['required', 'in:free_question,paid_booking'],
            'topic' => ['nullable', 'string', 'max:255'],
            'question' => ['required', 'string', 'max:5000'],
        ];

        if (! Auth::check()) {
            $rules['guest_name'] = ['required', 'string', 'max:150'];
            $rules['guest_email'] = ['required', 'email', 'max:255'];
        }

        if ($this->type === ConsultationType::PAID_BOOKING->value) {
            $rules['preferred_datetime'] = ['required', 'date', 'after:now'];
        }

        return $rules;
    }

    public function submit(ConsultationService $service): void
    {
        $key = 'consultation-submit:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('question', __('consultation.rate_limited'));

            return;
        }

        RateLimiter::hit($key, 3600);

        $data = $this->validate();

        if (Auth::check()) {
            unset($data['guest_name'], $data['guest_email']);
        }

        if ($this->type !== ConsultationType::PAID_BOOKING->value) {
            unset($data['preferred_datetime']);
        }

        $service->submit($data);

        $this->reset(['guest_name', 'guest_email', 'topic', 'question', 'preferred_datetime']);
        $this->submitted = true;
    }

    public function render()
    {
        $seo = [
            'title' => __('consultation.page_title'),
            'description' => __('consultation.page_intro'),
            'image' => null,
            'type' => 'website',
            'schema' => null,
        ];

        return view('livewire.consultation.consultation-form', compact('seo'));
    }
}
