<?php

use App\Modules\Consultation\Livewire\ConsultationForm;
use App\Modules\Consultation\Models\Consultation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(fn () => RateLimiter::clear('consultation-submit:127.0.0.1'));

function fillGuestConsultation($component)
{
    return $component->set('guest_name', 'Guest')->set('guest_email', 'g@example.com')->set('question', 'My question');
}

it('renders the consultation page', function () {
    $this->get('/en/consultation')->assertOk();
});

it('validates a guest submission', function () {
    Livewire::test(ConsultationForm::class)
        ->call('submit')
        ->assertHasErrors(['question', 'guest_name', 'guest_email']);
});

it('requires a date for paid bookings', function () {
    fillGuestConsultation(Livewire::test(ConsultationForm::class))
        ->set('type', 'paid_booking')
        ->call('submit')
        ->assertHasErrors(['preferred_datetime']);
});

it('saves a guest request as pending', function () {
    fillGuestConsultation(Livewire::test(ConsultationForm::class))
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('submitted', true);

    $c = Consultation::firstOrFail();
    expect($c->guest_email)->toBe('g@example.com')
        ->and($c->status->value)->toBe('pending')
        ->and($c->user_id)->toBeNull();
});

it('links a logged in user request to the user', function () {
    $user = userWithRole();

    Livewire::actingAs($user)->test(ConsultationForm::class)
        ->set('question', 'Q')->call('submit')->assertHasNoErrors();

    expect(Consultation::firstOrFail()->user_id)->toBe($user->id);
});

it('rate limits after five submissions', function () {
    $component = Livewire::test(ConsultationForm::class);
    foreach (range(1, 6) as $i) {
        fillGuestConsultation($component)->call('submit');
    }
    $component->assertHasErrors(['question']);

    expect(Consultation::count())->toBe(5);
});

it('serves the signed view only with a valid signature', function () {
    $c = makeConsultation(userWithRole());
    $params = ['locale' => 'en', 'consultation' => $c->getRouteKey()];

    $signed = URL::temporarySignedRoute('consultations.signed-view', now()->addDay(), $params);
    $this->get($signed)->assertOk();
    $this->get($signed.'x')->assertForbidden();
    $this->get(route('consultations.signed-view', $params))->assertForbidden();
});

it('applies the consultation policy', function () {
    $owner = userWithRole();
    $other = userWithRole();
    $staff = userWithRole('admin');
    $c = makeConsultation($owner);

    expect(Gate::forUser($owner)->allows('view', $c))->toBeTrue()
        ->and(Gate::forUser($other)->allows('view', $c))->toBeFalse()
        ->and(Gate::forUser($staff)->allows('view', $c))->toBeTrue()
        ->and(Gate::forUser($other)->allows('update', $c))->toBeFalse();
});
