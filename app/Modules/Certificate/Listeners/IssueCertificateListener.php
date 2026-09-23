<?php

namespace App\Modules\Certificate\Listeners;

use App\Modules\Certificate\Models\Certificate;
use App\Modules\Course\Events\CourseCompleted;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;

class IssueCertificateListener implements ShouldQueue
{
    public function handle(CourseCompleted $event): void
    {
        $enrollment = $event->enrollment->loadMissing(['user', 'course']);

        $certificate = Certificate::create([
            'verification_code' => $this->generateVerificationCode(),
            'user_id' => $enrollment->user_id,
            'course_id' => $enrollment->course_id,
            'issued_at' => now(),
        ]);

        $pdf = Pdf::loadView('certificates.pdf', [
            'certificate' => $certificate,
            'user' => $enrollment->user,
            'course' => $enrollment->course,
        ]);

        $certificate->addMediaFromString($pdf->output())
            ->usingFileName("certificate-{$certificate->verification_code}.pdf")
            ->toMediaCollection('certificate_pdf');
    }

    /**
     * Format: AZ-{year}-{6 digit}, blueprint A13. Loop guards against the
     * (rare) collision from random_int.
     */
    private function generateVerificationCode(): string
    {
        do {
            $code = 'AZ-'.now()->year.'-'.str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (Certificate::where('verification_code', $code)->exists());

        return $code;
    }
}
