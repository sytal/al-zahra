<?php

namespace App\Modules\Consultation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Consultation\Models\Consultation;
use Illuminate\View\View;

class ConsultationSignedViewController extends Controller
{
    public function __invoke(string $locale, Consultation $consultation): View
    {
        return view('consultations.signed-view', compact('consultation'));
    }
}
