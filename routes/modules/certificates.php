<?php

use App\Modules\Certificate\Livewire\CertificateVerify;
use Illuminate\Support\Facades\Route;

// Blueprint C19 shows GET/POST /{locale}/certificates/verify with separate
// .form/.check route names, but this follows the same single full-page
// Livewire component pattern already used for consultation/contact: the
// component handles its own submission internally via wire:submit, so a
// separate POST "check" route is not needed — only the form route exists.
Route::get('/certificates/verify', CertificateVerify::class)->name('certificates.verify.form');
