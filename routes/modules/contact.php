<?php

use App\Modules\Contact\Livewire\ContactForm;
use Illuminate\Support\Facades\Route;

Route::get('/contact', ContactForm::class)->name('contact.show');
