<?php

use App\Modules\Article\Repositories\ArticleRepositoryInterface;
use App\Modules\Research\Repositories\ResearchPaperRepositoryInterface;
use App\Modules\Resource\Repositories\ResourceRepositoryInterface;
use App\Support\Enums\ResourceType;
use Illuminate\Support\Facades\Cache;

beforeEach(fn () => Cache::flush());

it('matches article titles with a partial case-insensitive search', function () {
    makeArticle(['title' => ['en' => 'Bilingual Vocabulary Growth']]);
    makeArticle(['title' => ['en' => 'Sleep and memory']]);

    $repo = app(ArticleRepositoryInterface::class);

    expect($repo->paginatePublished(null, 'vocab')->total())->toBe(1)
        ->and($repo->paginatePublished(null, 'zzz')->total())->toBe(0);
});

it('treats percent and underscore in search as literal characters', function () {
    makeArticle(['title' => ['en' => 'Plain title']]);

    expect(app(ArticleRepositoryInterface::class)->paginatePublished(null, '%')->total())->toBe(0);
});

it('reports published article totals in listStats', function () {
    makeArticle();
    makeArticle();
    makeArticle(['is_published' => false]);

    $stats = app(ArticleRepositoryInterface::class)->listStats();

    expect($stats['total'])->toBe(2)->and($stats['categories'])->toBe([]);
});

it('filters research by year and reports years in listStats', function () {
    makeResearch(['published_year' => 2023]);
    makeResearch(['published_year' => 2023]);
    makeResearch(['published_year' => 2025]);
    makeResearch(['published_year' => 2021, 'is_published' => false]);

    $repo = app(ResearchPaperRepositoryInterface::class);
    $stats = $repo->listStats();

    expect($stats['total'])->toBe(3)
        ->and($stats['years'])->toBe([2025 => 1, 2023 => 2])
        ->and($repo->paginatePublished(null, null, 12, 2023)->total())->toBe(2);
});

it('filters resources by type and pricing and reports counts in listStats', function () {
    makeResource(['resource_type' => ResourceType::PDF, 'is_free' => true]);
    makeResource(['resource_type' => ResourceType::PDF, 'is_free' => false]);
    makeResource(['resource_type' => ResourceType::GUIDE, 'is_free' => true]);

    $repo = app(ResourceRepositoryInterface::class);
    $stats = $repo->listStats();

    expect($stats['total'])->toBe(3)
        ->and($stats['free'])->toBe(2)
        ->and($stats['types']['pdf'])->toBe(2)
        ->and($repo->paginatePublished(null, null, 12, 'pdf')->total())->toBe(2)
        ->and($repo->paginatePublished(null, null, 12, null, 'free')->total())->toBe(2)
        ->and($repo->paginatePublished(null, null, 12, 'pdf', 'paid')->total())->toBe(1);
});
