<?php

namespace App\Modules\Article\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Article\Repositories\ArticleRepositoryInterface;
use App\Modules\Article\Services\ArticleService;
use App\Support\SeoSchema;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function __construct(
        private readonly ArticleRepositoryInterface $repository,
        private readonly ArticleService $service,
    ) {}

    public function show(Request $request, string $locale, string $slug): View
    {
        $article = $this->repository->findPublishedBySlug($slug) ?? abort(404);

        if (! $request->session()->get("viewed_article_{$article->id}")) {
            $this->service->recordView($article);
            $request->session()->put("viewed_article_{$article->id}", true);
        }

        $related = $this->repository->relatedTo($article);

        $seo = [
            'title' => $article->meta_title ?: $article->title,
            'description' => $article->meta_description ?: $article->excerpt,
            'image' => $article->getFirstMediaUrl('featured_image', 'hero') ?: null,
            'type' => 'article',
            'schema' => SeoSchema::article($article),
        ];

        return view('articles.show', compact('article', 'related', 'seo'));
    }
}
