<?php

// Routes for dashboard.consultations.*, dashboard.certificates.*,
// dashboard.profile.* (C14, C15, C16).

use App\Modules\Certificate\Http\Controllers\CertificateDownloadController;
use App\Modules\Certificate\Livewire\DashboardCertificateIndex;
use App\Modules\Consultation\Livewire\DashboardConsultationIndex;
use App\Modules\User\Livewire\ProfileEdit;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard/consultations', DashboardConsultationIndex::class)->name('dashboard.consultations.index');

Route::get('/dashboard/certificates', DashboardCertificateIndex::class)->name('dashboard.certificates.index');
Route::get('/dashboard/certificates/{certificate}/download', CertificateDownloadController::class)->name('dashboard.certificates.download');

Route::get('/dashboard/profile', ProfileEdit::class)->name('dashboard.profile.edit');

// Updates happen in-place via the ProfileEdit Livewire component's own
// wire:submit actions (updateProfile/updateAvatar/updatePassword) rather
// than a classic form POST, so this named route exists to satisfy the
// blueprint's route-name contract but is not itself invoked.
Route::put('/dashboard/profile', ProfileEdit::class)->name('dashboard.profile.update');
