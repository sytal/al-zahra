<?php

use App\Modules\Consultation\Models\Consultation;
use Illuminate\Support\Facades\URL;

it('keeps AOS attributes off Livewire form roots that would go blank after a morph', function (string $path) {
    $this->get($path)->assertOk()->assertDontSee('data-aos="fade-up"', false);
})->with(['/en/contact', '/en/consultation', '/en/courses']);

it('renders the signed consultation question with automatic text direction', function () {
    $consultation = Consultation::create([
        'guest_name' => 'Ali',
        'guest_email' => 'ali@example.com',
        'question' => 'Mixed direction question?',
        'type' => 'free_question',
        'status' => 'pending',
    ]);

    $url = URL::temporarySignedRoute('consultations.signed-view', now()->addHour(), ['locale' => 'ur', 'consultation' => $consultation->getRouteKey()]);

    $this->get($url)->assertOk()->assertSee('dir="auto"', false);
});
