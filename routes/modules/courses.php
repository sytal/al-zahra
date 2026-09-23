<?php

use App\Modules\Course\Http\Controllers\CourseController;
use App\Modules\Course\Livewire\CourseIndex;
use Illuminate\Support\Facades\Route;

Route::get('/courses', CourseIndex::class)->name('courses.index');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
Route::post('/courses/{slug}/enroll', [CourseController::class, 'enroll'])
    ->middleware('auth')
    ->name('courses.enroll');
