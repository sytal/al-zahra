<?php

namespace App\Modules\Article\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Article\Repositories\ArticleRepositoryInterface;
use App\Modules\Article\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function __construct(
        private readonly ArticleRepositoryInterface $repository,
        private readonly ArticleService $service,
    ) {}

    public function show(string $slug, Request $request): View
    {
        $article = $this->repository->findPublishedBySlug($slug) ?? abort(404);

        if (! $request->session()->get("viewed_article_{$article->id}")) {
            $this->service->recordView($article);
            $request->session()->put("viewed_article_{$article->id}", true);
        }

        $related = $this->repository->relatedTo($article);

        return view('articles.show', compact('article', 'related'));
    }
}
