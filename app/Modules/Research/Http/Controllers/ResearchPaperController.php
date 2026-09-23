<?php

namespace App\Modules\Research\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Research\Repositories\ResearchPaperRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResearchPaperController extends Controller
{
    public function __construct(
        private readonly ResearchPaperRepositoryInterface $repository,
    ) {}

    public function show(Request $request, string $locale, string $slug): View
    {
        $paper = $this->repository->findPublishedBySlug($slug) ?? abort(404);
        $related = $this->repository->relatedTo($paper);

        $seo = [
            'title' => $paper->meta_title ?: $paper->title,
            'description' => $paper->meta_description ?: $paper->significance,
            'image' => $paper->getFirstMediaUrl('cover_image', 'card') ?: null,
            'type' => 'article',
            'schema' => null,
        ];

        return view('research.show', compact('paper', 'related', 'seo'));
    }
}
