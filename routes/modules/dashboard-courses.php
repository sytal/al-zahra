<?php

use App\Modules\Course\Livewire\LessonViewer;
use App\Modules\Course\Livewire\MyCourses;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard/my-courses', MyCourses::class)->name('dashboard.courses.index');

Route::get('/dashboard/courses/{course:slug}/learn', LessonViewer::class)->name('dashboard.courses.learn');
Route::get('/dashboard/courses/{course:slug}/learn/{lesson:uuid}', LessonViewer::class)->name('dashboard.courses.lesson');
