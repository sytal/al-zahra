<?php

namespace App\Modules\Article\Services;

use App\Modules\Article\Models\Article;
use App\Modules\Article\Repositories\ArticleRepositoryInterface;
use Illuminate\Support\Str;

class ArticleService
{
    public function __construct(
        private readonly ArticleRepositoryInterface $repository,
    ) {}

    public function create(array $data): Article
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']['en'] ?? $data['title']);
        $data['reading_time_minutes'] = $this->estimateReadingTime($data['body'] ?? []);

        return Article::create($data);
    }

    public function update(Article $article, array $data): Article
    {
        if (isset($data['body'])) {
            $data['reading_time_minutes'] = $this->estimateReadingTime($data['body']);
        }

        $article->update($data);

        return $article;
    }

    public function publish(Article $article): Article
    {
        $article->update([
            'is_published' => true,
            'published_at' => $article->published_at ?? now(),
        ]);

        return $article;
    }

    public function unpublish(Article $article): Article
    {
        $article->update(['is_published' => false]);

        return $article;
    }

    public function recordView(Article $article): void
    {
        $article->increment('views_count');
    }

    private function estimateReadingTime(array|string $body): int
    {
        $text = is_array($body) ? implode(' ', $body) : $body;
        $wordCount = str_word_count(strip_tags($text));

        return max(1, (int) ceil($wordCount / 200));
    }
}
