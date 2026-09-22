<?php

namespace App\Support;

use App\Modules\Article\Models\Article;
use App\Modules\Course\Models\Course;
use App\Modules\Director\Models\Director;

class SeoSchema
{
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
