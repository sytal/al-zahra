<?php

use App\Modules\Course\Livewire\CourseCompleteScreen;
use App\Modules\Course\Livewire\CourseLearn;
use App\Modules\Course\Livewire\LessonViewer;
use App\Modules\Course\Livewire\MyCourses;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard/my-courses', MyCourses::class)->name('dashboard.courses.index');

Route::get('/dashboard/courses/{course:slug}/learn', LessonViewer::class)->name('dashboard.courses.learn');
Route::get('/dashboard/courses/{course:slug}/learn/{lesson:uuid}', LessonViewer::class)->name('dashboard.courses.lesson');

// Modules+blocks course structure (course-builder plan) — separate from the
// flat-lesson LessonViewer above, which stays for the old course_lessons data.
Route::get('/dashboard/courses/{course:slug}/study', CourseLearn::class)->name('dashboard.courses.study');
Route::get('/dashboard/courses/{course:slug}/study/{module:uuid}', CourseLearn::class)->name('dashboard.courses.study-module');
Route::get('/dashboard/courses/{course:slug}/complete', CourseCompleteScreen::class)->name('dashboard.courses.complete');
