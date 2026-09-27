<?php

namespace App\Modules\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Article\Models\Article;
use App\Modules\Article\Repositories\ArticleRepositoryInterface;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Models\Enrollment;
use App\Modules\Director\Models\Director;
use App\Modules\Research\Models\ResearchPaper;
use App\Modules\Research\Repositories\ResearchPaperRepositoryInterface;
use App\Modules\Setting\Models\Setting;
use App\Support\CacheService;
use App\Support\SeoSchema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articles,
        private readonly ResearchPaperRepositoryInterface $research,
        private readonly CacheService $cache,
    ) {}

    public function index(string $locale): View
    {
        $latestArticles = $this->articles->paginatePublished(perPage: 3);
        $featuredCourses = Course::where('is_published', true)->withCount('lessons')->latest()->limit(3)->get();
        $latestResearch = $this->research->paginatePublished(perPage: 2);

        $stats = $this->cache->remember('home:stats:counts', CacheService::LIST_TTL, fn () => [
            'articles' => Article::where('is_published', true)->count(),
            'courses' => Course::where('is_published', true)->count(),
            'papers' => ResearchPaper::where('is_published', true)->count(),
            'learners' => Enrollment::query()->distinct()->count('user_id'),
        ]);

        $publications = $this->cache->remember('home:publications:'.$locale, CacheService::LIST_TTL, fn () => ResearchPaper::where('is_published', true)
            ->latest('published_year')->limit(8)->get()
            ->map(fn (ResearchPaper $p) => [
                'title' => $p->title,
                'meta' => $p->published_year ? (string) $p->published_year : null,
                'url' => route('research.show', ['locale' => $locale, 'slug' => $p->slug]),
            ])->all());

        $testimonials = collect(Setting::where('key', 'testimonials')->value('value') ?? [])
            ->map(fn ($t) => [
                'quote' => is_array($t['quote'] ?? null) ? ($t['quote'][$locale] ?? $t['quote']['en'] ?? '') : ($t['quote'] ?? ''),
                'name' => is_array($t['name'] ?? null) ? ($t['name'][$locale] ?? $t['name']['en'] ?? '') : ($t['name'] ?? ''),
                'role' => is_array($t['role'] ?? null) ? ($t['role'][$locale] ?? $t['role']['en'] ?? '') : ($t['role'] ?? ''),
                'demo' => (bool) ($t['demo'] ?? false),
            ])
            ->filter(fn ($t) => $t['quote'] !== '')
            ->values();

        $tagline = Setting::where('key', 'site_tagline')->value('value');
        $tagline = is_array($tagline) ? ($tagline[$locale] ?? $tagline['en'] ?? '') : (string) $tagline;
        $director = Director::where('is_published', true)->first();
        $description = trim($tagline.' '.__('home.hero_subtext'));

        $seo = [
            'title' => $tagline !== '' ? $tagline : __('home_ui.eyebrow'),
            'description' => $description,
            'image' => $director?->getFirstMediaUrl('cover_photo') ?: null,
            'type' => 'website',
            'schema' => SeoSchema::graph(
                SeoSchema::organization(),
                SeoSchema::website(url($locale), $description),
                ...($director ? [SeoSchema::person($director)] : []),
            ),
        ];

        return view('home', [
            'seo' => $seo,
            'tagline' => $tagline,
            'latestArticles' => $latestArticles,
            'featuredCourses' => $featuredCourses,
            'latestResearch' => $latestResearch,
            'stats' => $stats,
            'publications' => $publications,
            'testimonials' => $testimonials,
            'director' => $director,
        ]);
    }
}
