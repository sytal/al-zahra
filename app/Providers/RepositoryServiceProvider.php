<?php

namespace App\Providers;

use App\Modules\Article\Repositories\ArticleRepository;
use App\Modules\Article\Repositories\ArticleRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
    }
}
