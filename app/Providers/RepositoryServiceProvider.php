<?php

namespace App\Providers;

use App\Modules\Article\Repositories\ArticleRepository;
use App\Modules\Article\Repositories\ArticleRepositoryInterface;
use App\Modules\Course\Repositories\CourseRepository;
use App\Modules\Course\Repositories\CourseRepositoryInterface;
use App\Modules\Research\Repositories\ResearchPaperRepository;
use App\Modules\Research\Repositories\ResearchPaperRepositoryInterface;
use App\Modules\Resource\Repositories\ResourceRepository;
use App\Modules\Resource\Repositories\ResourceRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(ResearchPaperRepositoryInterface::class, ResearchPaperRepository::class);
        $this->app->bind(ResourceRepositoryInterface::class, ResourceRepository::class);
    }
}
