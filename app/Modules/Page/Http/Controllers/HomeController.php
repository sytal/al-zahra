<?php

namespace App\Modules\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Article\Repositories\ArticleRepositoryInterface;
use App\Modules\Course\Models\Course;
use App\Modules\Research\Repositories\ResearchPaperRepositoryInterface;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articles,
        private readonly ResearchPaperRepositoryInterface $research,
    ) {}

    public function index(string $locale): View
    {
        $latestArticles = $this->articles->paginatePublished(perPage: 3);
        $featuredCourses = Course::where('is_published', true)->latest()->limit(3)->get();
        $latestResearch = $this->research->paginatePublished(perPage: 2);

        return view('home', [
            'latestArticles' => $latestArticles,
            'featuredCourses' => $featuredCourses,
            'latestResearch' => $latestResearch,
        ]);
    }
}
