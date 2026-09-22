<?php

namespace App\Console\Commands;

use App\Modules\Article\Models\Article;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the public sitemap.xml from published content';

    public function handle(): void
    {
        $sitemap = Sitemap::create();

        foreach (config('app.locales') as $locale) {
            $sitemap->add(Url::create("/{$locale}")->setPriority(1.0));
            $sitemap->add(Url::create("/{$locale}/articles")->setPriority(0.8));

            Article::query()
                ->where('is_published', true)
                ->each(function (Article $article) use ($sitemap, $locale) {
                    $sitemap->add(
                        Url::create("/{$locale}/articles/{$article->slug}")
                            ->setLastModificationDate($article->updated_at)
                            ->setPriority(0.6)
                    );
                });
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated.');
    }
}
