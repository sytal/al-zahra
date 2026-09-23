<?php

namespace App\Modules\Certificate\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Certificate\Models\Certificate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CertificateDownloadController extends Controller
{
    public function __invoke(Request $request, string $locale, Certificate $certificate): RedirectResponse
    {
        abort_if($certificate->user_id !== $request->user()->id, 403);

        if (! $certificate->hasMedia('certificate_pdf')) {
            return back()->with('error', __('dashboard.certificates_pdf_preparing'));
        }

        return redirect()->away($certificate->getFirstMediaUrl('certificate_pdf'));
    }
}
