<?php

namespace App\Modules\Certificate\Services;

use App\Modules\Certificate\Models\Certificate;
use Mpdf\Mpdf;

/**
 * mPDF shapes Arabic-script text (joining, Urdu/Persian forms, bidi) natively,
 * which dompdf cannot do.
 */
class CertificatePdfRenderer
{
    public function render(Certificate $certificate): string
    {
        $certificate->loadMissing(['user', 'course']);

        $html = view('certificates.pdf', [
            'certificate' => $certificate,
            'user' => $certificate->user,
            'course' => $certificate->course,
        ])->render();

        $tempDir = storage_path('app/mpdf');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'tempDir' => $tempDir,
            'default_font' => 'xbriyaz',
            'useSubstitutions' => true,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
        ]);

        $mpdf->SetTitle(__('certificates.pdf_title'));
        $mpdf->WriteHTML($html);

        return $mpdf->Output('', 'S');
    }
}
