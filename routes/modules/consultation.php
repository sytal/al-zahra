<?php

use App\Modules\Consultation\Http\Controllers\ConsultationSignedViewController;
use App\Modules\Consultation\Livewire\ConsultationForm;
use Illuminate\Support\Facades\Route;

Route::get('/consultation', ConsultationForm::class)->name('consultation.show');

// Blueprint shows /view/{signature} as a URI segment, but Laravel's
// temporarySignedRoute() puts the signature in the query string
// (?signature=...), not the path — a literal {signature} segment would
// never match a real signed URL. Following the actual Laravel Signed
// URLs mechanism (docs/CLAUDE.md Section 17) instead of the literal
// route text.
Route::get('/consultations/{consultation}/view', ConsultationSignedViewController::class)
    ->middleware('signed')
    ->name('consultations.signed-view');
