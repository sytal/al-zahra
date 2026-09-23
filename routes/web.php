<?php

use App\Http\Controllers\ProfileController;
use App\Modules\Page\Http\Controllers\HomeController;
use App\Modules\Page\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/'.config('app.locale'));

Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => implode('|', config('app.locales'))],
    'middleware' => 'setlocale',
], function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/about', [PageController::class, 'about'])->name('about');

        require __DIR__.'/modules/articles.php';
        require __DIR__.'/modules/courses.php';
        require __DIR__.'/modules/research.php';
        require __DIR__.'/modules/resources.php';
        require __DIR__.'/modules/consultation.php';

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
