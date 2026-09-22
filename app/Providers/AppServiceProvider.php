<?php

namespace App\Providers;

use App\Modules\Article\Livewire\ArticleIndex;
use App\Modules\Newsletter\Livewire\NewsletterForm;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * Livewire components live in each module's own Livewire/ folder
     * (docs/CLAUDE.md Section 4), so auto-discovery (which only scans
     * app/Livewire) can't find them — register each one's tag manually.
     */
    public function boot(): void
    {
        Livewire::component('articles.article-index', ArticleIndex::class);
        Livewire::component('newsletter.newsletter-form', NewsletterForm::class);
    }
}
