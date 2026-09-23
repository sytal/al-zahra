<?php

use App\Modules\Research\Http\Controllers\ResearchPaperController;
use App\Modules\Research\Livewire\ResearchIndex;
use Illuminate\Support\Facades\Route;

Route::get('/research', ResearchIndex::class)->name('research.index');
Route::get('/research/{slug}', [ResearchPaperController::class, 'show'])->name('research.show');
