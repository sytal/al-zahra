<?php

use App\Modules\Certificate\Services\CertificatePdfRenderer;
use Illuminate\Support\Facades\Storage;

it('renders Arabic-script names into a valid pdf', function () {
    $owner = userWithRole('student', ['name' => 'محمد عادل']);
    $cert = makeCertificate($owner, makeCourse(['title' => ['en' => 'Intro', 'ur' => 'فقہ کا تعارف']]));

    $pdf = app(CertificatePdfRenderer::class)->render($cert);

    expect(str_starts_with($pdf, '%PDF'))->toBeTrue();
});

it('serves the generated pdf to its owner only', function () {
    Storage::fake('public');
    config(['media-library.disk_name' => 'public']);

    $owner = userWithRole();
    $other = userWithRole();
    $cert = makeCertificate($owner);
    $cert->addMediaFromString(app(CertificatePdfRenderer::class)->render($cert))
        ->usingFileName('certificate.pdf')
        ->toMediaCollection('certificate_pdf');
    $url = route('dashboard.certificates.download', ['locale' => 'en', 'certificate' => $cert->getRouteKey()]);

    $this->actingAs($other)->get($url)->assertForbidden();

    $response = $this->actingAs($owner)->get($url)->assertRedirect();
    expect($response->headers->get('Location'))->toContain('certificate.pdf');
});
