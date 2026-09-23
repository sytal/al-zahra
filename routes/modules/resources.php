<?php

use App\Modules\Resource\Http\Controllers\ResourceController;
use App\Modules\Resource\Livewire\ResourceIndex;
use Illuminate\Support\Facades\Route;

Route::get('/resources', ResourceIndex::class)->name('resources.index');
Route::get('/resources/{slug}', [ResourceController::class, 'show'])->name('resources.show');
Route::post('/resources/{slug}/download', [ResourceController::class, 'download'])->name('resources.download');
