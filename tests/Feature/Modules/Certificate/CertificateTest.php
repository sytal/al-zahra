<?php

use App\Modules\Certificate\Livewire\CertificateVerify;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;

beforeEach(fn () => RateLimiter::clear('certificate-verify:127.0.0.1'));

it('renders the verify page', function () {
    $this->get('/en/certificates/verify')->assertOk();
});

it('requires a code', function () {
    Livewire::test(CertificateVerify::class)->call('verify')->assertHasErrors(['code']);
});

it('finds a certificate case-insensitively', function () {
    $cert = makeCertificate(userWithRole());

    Livewire::test(CertificateVerify::class)
        ->set('code', strtolower($cert->verification_code))
        ->call('verify')
        ->assertSet('checked', true)
        ->assertSet('certificate.id', $cert->id);
});

it('reports an unknown code as not found', function () {
    Livewire::test(CertificateVerify::class)
        ->set('code', 'NOPE-0000')
        ->call('verify')
        ->assertSet('checked', true)
        ->assertSet('certificate', null);
});

it('lets only the owner download a certificate', function () {
    $owner = userWithRole();
    $other = userWithRole();
    $cert = makeCertificate($owner);
    $url = route('dashboard.certificates.download', ['locale' => 'en', 'certificate' => $cert->getRouteKey()]);

    $this->actingAs($other)->get($url)->assertForbidden();
    $this->actingAs($owner)->get($url)->assertRedirect()->assertSessionHas('error');
});

it('applies the certificate policy', function () {
    $owner = userWithRole();
    $other = userWithRole();
    $staff = userWithRole('admin');
    $cert = makeCertificate($owner);

    expect(Gate::forUser($owner)->allows('view', $cert))->toBeTrue()
        ->and(Gate::forUser($other)->allows('view', $cert))->toBeFalse()
        ->and(Gate::forUser($staff)->allows('view', $cert))->toBeTrue();
});
