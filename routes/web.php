<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/'.config('app.locale'));

Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => implode('|', config('app.locales'))],
    'middleware' => 'setlocale',
], function () {
        Route::get('/', function () {
            return view('welcome');
        })->name('home');

        require __DIR__.'/modules/articles.php';
        require __DIR__.'/modules/courses.php';
        require __DIR__.'/modules/research.php';

        Route::get('/dashboard', function () {
            return view('dashboard');
        })->middleware(['auth', 'verified'])->name('dashboard');

        Route::middleware('auth')->group(function () {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });

        require __DIR__.'/auth.php';
    });
