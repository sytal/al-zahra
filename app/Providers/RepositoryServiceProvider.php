<?php

namespace App\Providers;

use App\Modules\Article\Repositories\ArticleRepository;
use App\Modules\Article\Repositories\ArticleRepositoryInterface;
use App\Modules\Course\Repositories\CourseRepository;
use App\Modules\Course\Repositories\CourseRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
    }
}
