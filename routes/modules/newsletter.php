<?php

use App\Modules\Newsletter\Http\Controllers\NewsletterConfirmController;
use Illuminate\Support\Facades\Route;

// The Livewire NewsletterForm component handles its own POST /subscribe
// submission internally via wire:submit, so no separate subscribe route
// is registered here (same pattern as consultation/contact).

// Blueprint shows /confirm/{subscriber}/{signature} as two URI segments,
// but Laravel's temporarySignedRoute() puts the signature in the query
// string (?signature=...), not the path — a literal {signature} segment
// would never match a real signed URL. Following the actual Laravel
// Signed URLs mechanism (docs/CLAUDE.md Section 17), matching the same
// deviation already documented in routes/modules/consultation.php for
// consultations.signed-view.
Route::get('/newsletter/confirm/{subscriber}', NewsletterConfirmController::class)
    ->middleware('signed')
    ->name('newsletter.confirm');
