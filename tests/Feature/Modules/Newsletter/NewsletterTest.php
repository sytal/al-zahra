<?php

use App\Modules\Newsletter\Livewire\NewsletterForm;
use App\Modules\Newsletter\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

function confirmUrl(NewsletterSubscriber $sub): string
{
    return URL::temporarySignedRoute('newsletter.confirm', now()->addDay(), ['locale' => 'en', 'subscriber' => $sub->getRouteKey()]);
}

it('validates the subscribe email', function () {
    Livewire::test(NewsletterForm::class)
        ->set('email', 'bad')
        ->call('subscribe')
        ->assertHasErrors(['email']);
});

it('stores an unconfirmed subscriber without duplicating', function () {
    Livewire::test(NewsletterForm::class)->set('email', 'a@example.com')->call('subscribe')->assertSet('subscribed', true);
    Livewire::test(NewsletterForm::class)->set('email', 'a@example.com')->call('subscribe');

    $subs = NewsletterSubscriber::where('email', 'a@example.com')->get();
    expect($subs)->toHaveCount(1)
        ->and($subs->first()->is_confirmed)->toBeFalse();
});

it('confirms via a valid signed link', function () {
    $sub = NewsletterSubscriber::create(['email' => 'b@example.com', 'locale' => 'en']);

    $this->get(confirmUrl($sub))->assertOk();

    expect($sub->fresh()->is_confirmed)->toBeTrue();
});

it('rejects an unsigned or tampered confirm link', function () {
    $sub = NewsletterSubscriber::create(['email' => 'c@example.com', 'locale' => 'en']);

    $this->get(route('newsletter.confirm', ['locale' => 'en', 'subscriber' => $sub->getRouteKey()]))->assertForbidden();
    $this->get(confirmUrl($sub).'x')->assertForbidden();

    expect($sub->fresh()->is_confirmed)->toBeFalse();
});
