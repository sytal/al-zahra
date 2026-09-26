<?php

namespace App\Console\Commands;

use App\Modules\Article\Models\Article;
use App\Modules\Course\Models\Course;
use App\Modules\Research\Models\ResearchPaper;
use App\Modules\Resource\Models\Resource;
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

        foreach (['' => 1.0, '/about' => 0.7, '/articles' => 0.8, '/courses' => 0.8, '/research' => 0.8, '/resources' => 0.8, '/consultation' => 0.6, '/contact' => 0.5, '/certificates/verify' => 0.3] as $path => $priority) {
            $this->addLocalized($sitemap, $path, $priority);
        }

        $groups = [
            ['articles', Article::class, 0.6],
            ['courses', Course::class, 0.7],
            ['research', ResearchPaper::class, 0.6],
            ['resources', Resource::class, 0.5],
        ];

        foreach ($groups as [$segment, $model, $priority]) {
            $model::query()->where('is_published', true)->each(
                fn ($item) => $this->addLocalized($sitemap, "/{$segment}/{$item->slug}", $priority, $item->updated_at)
            );
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated.');
    }

    private function addLocalized(Sitemap $sitemap, string $path, float $priority, $modified = null): void
    {
        foreach (config('app.locales') as $locale) {
            $url = Url::create("/{$locale}{$path}")->setPriority($priority);

            if ($modified) {
                $url->setLastModificationDate($modified);
            }

            foreach (config('app.locales') as $alt) {
                $url->addAlternate("/{$alt}{$path}", $alt);
            }

            $sitemap->add($url);
        }
    }
}
