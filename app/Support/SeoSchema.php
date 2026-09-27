<?php

namespace App\Support;

use App\Modules\Article\Models\Article;
use App\Modules\Course\Models\Course;
use App\Modules\Director\Models\Director;

class SeoSchema
{
    /**
     * @param  iterable<int, array{name: string, url: string}>  $items
     */
    public static function collectionPage(string $name, string $description, string $url, iterable $items): array
    {
        $elements = [];
        $position = 1;
        foreach ($items as $item) {
            $elements[] = ['@type' => 'ListItem', 'position' => $position++, 'name' => $item['name'], 'url' => $item['url']];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $name,
            'description' => $description,
            'url' => $url,
            'inLanguage' => app()->getLocale(),
            'isPartOf' => ['@type' => 'WebSite', 'name' => config('app.name'), 'url' => url('/')],
            'mainEntity' => ['@type' => 'ItemList', 'numberOfItems' => $position - 1, 'itemListElement' => $elements],
        ];
    }

    public static function article(Article $article): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article->title,
            'description' => $article->excerpt,
            'image' => $article->getFirstMediaUrl('featured_image', 'hero') ?: null,
            'datePublished' => $article->published_at?->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $article->author?->name,
            ],
        ];
    }

    public static function course(Course $course): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Course',
            'name' => $course->title,
            'description' => $course->short_description,
            'provider' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
            ],
        ];
    }

    public static function person(Director $director): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $director->full_name,
            'jobTitle' => $director->professional_title,
            'description' => $director->bio_short,
            'image' => $director->getFirstMediaUrl('profile_photo') ?: null,
        ];
    }

    public static function organization(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('app.name'),
            'url' => url('/'),
        ];
    }

    public static function website(string $url, ?string $description = null): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name'),
            'url' => $url,
            'inLanguage' => app()->getLocale(),
            'description' => $description,
        ];
    }

    public static function graph(array ...$nodes): array
    {
        return [
            '@context' => 'https://schema.org',
            '@graph' => collect($nodes)->map(function (array $node) {
                unset($node['@context']);

                return array_filter($node, fn ($v) => $v !== null && $v !== '');
            })->values()->all(),
        ];
    }

    public static function breadcrumb(array $items): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)->values()->map(fn (array $item, int $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['label'],
                'item' => $item['url'] ?? null,
            ])->all(),
        ];
    }
}
