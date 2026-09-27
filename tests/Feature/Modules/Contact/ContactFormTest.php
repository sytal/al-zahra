<?php

use App\Modules\Contact\Livewire\ContactForm;
use App\Modules\Contact\Models\ContactMessage;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;

beforeEach(fn () => RateLimiter::clear('contact-submit:127.0.0.1'));

function fillContact($component)
{
    return $component->set('name', 'Ali')->set('email', 'ali@example.com')->set('subject', 'Hello')->set('message', 'Body text');
}

it('renders the contact page', function () {
    $this->get('/en/contact')->assertOk();
});

it('validates required fields', function () {
    Livewire::test(ContactForm::class)
        ->call('submit')
        ->assertHasErrors(['name', 'email', 'subject', 'message']);

    expect(ContactMessage::count())->toBe(0);
});

it('rejects an invalid email', function () {
    fillContact(Livewire::test(ContactForm::class))
        ->set('email', 'nope')
        ->call('submit')
        ->assertHasErrors(['email']);
});

it('saves a valid message', function () {
    fillContact(Livewire::test(ContactForm::class))
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('submitted', true);

    expect(ContactMessage::where('email', 'ali@example.com')->count())->toBe(1);
});

it('rate limits after five submissions', function () {
    $component = Livewire::test(ContactForm::class);
    foreach (range(1, 5) as $i) {
        fillContact($component)->call('submit');
    }
    fillContact($component)->call('submit')->assertHasErrors(['message']);

    expect(ContactMessage::count())->toBe(5);
});

test('public form pages render a specific localized title and json-ld', function () {
    foreach (['en', 'ur'] as $locale) {
        foreach ([
            ['contact', 'seo_contact_title', 'ContactPage'],
            ['consultation', 'seo_consult_title', 'WebPage'],
            ['certificates/verify', 'seo_verify_title', 'WebPage'],
        ] as [$path, $key, $type]) {
            $title = trans('forms_ui.'.$key, [], $locale);
            $html = $this->get("/{$locale}/{$path}")->assertOk()->getContent();
            expect($html)->toContain('<title>'.e($title).' — '.config('app.name').'</title>')
                ->and($html)->toContain('"@type":"'.$type.'"')
                ->and(substr_count($html, '<title>'))->toBe(1);
        }
    }
});
