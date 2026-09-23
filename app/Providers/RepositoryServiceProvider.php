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

        // These repositories cache whole Eloquent models/collections/paginators via
        // App\Support\CacheService (docs/CLAUDE.md Section 13). Laravel 12's cache
        // config defaults 'serializable_classes' to false, which silently degrades
        // any cached object to __PHP_Incomplete_Class on retrieval regardless of
        // cache driver (database/redis/file all share this guard). Rather than
        // disabling that protection app-wide, explicitly allowlist only the classes
        // these 4 repositories actually cache (the models, their eager-loaded
        // relations, and the framework collection/pagination wrappers around them)
        // so cached results come back intact without weakening unserialize
        // protection for anything else. Set here (register(), which runs before any
        // cache store is resolved) since this wiring belongs to the repository/cache
        // layer, not config/cache.php.
        $existing = config('cache.serializable_classes');

        config(['cache.serializable_classes' => array_merge(
            is_array($existing) ? $existing : [],
            [
                \App\Modules\Article\Models\Article::class,
                \App\Modules\Article\Models\Tag::class,
                \App\Modules\Course\Models\Course::class,
                \App\Modules\Course\Models\CourseLesson::class,
                \App\Modules\Research\Models\ResearchPaper::class,
                \App\Modules\Resource\Models\Resource::class,
                \App\Modules\Category\Models\Category::class,
                \App\Models\User::class,
                \Illuminate\Database\Eloquent\Collection::class,
                \Illuminate\Support\Collection::class,
                \Illuminate\Pagination\LengthAwarePaginator::class,
                \Carbon\Carbon::class,
                \Illuminate\Support\Carbon::class,
            ]
        )]);
    }
}
