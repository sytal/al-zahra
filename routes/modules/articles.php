<?php

use App\Modules\Article\Http\Controllers\ArticleController;
use App\Modules\Article\Livewire\ArticleIndex;
use Illuminate\Support\Facades\Route;

Route::get('/articles', ArticleIndex::class)->name('articles.index');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');
