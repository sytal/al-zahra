<?php

namespace App\Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Course\Repositories\CourseRepositoryInterface;
use App\Modules\Course\Services\CourseService;
use App\Support\SeoSchema;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function __construct(
        private readonly CourseRepositoryInterface $repository,
        private readonly CourseService $service,
    ) {}

    public function show(Request $request, string $locale, string $slug): View
    {
        $course = $this->repository->findPublishedBySlug($slug) ?? abort(404);
        $related = $this->repository->relatedTo($course);

        $isEnrolled = $request->user() ? $this->service->isEnrolled($request->user(), $course) : false;

        $seo = [
            'title' => $course->meta_title ?: $course->title,
            'description' => $course->meta_description ?: $course->short_description,
            'image' => $course->getFirstMediaUrl('cover_image', 'hero') ?: null,
            'type' => 'website',
            'schema' => SeoSchema::course($course),
        ];

        return view('courses.show', compact('course', 'related', 'isEnrolled', 'seo'));
    }

    public function enroll(Request $request, string $locale, string $slug): RedirectResponse
    {
        $course = $this->repository->findPublishedBySlug($slug) ?? abort(404);

        $this->service->enroll($request->user(), $course);

        return redirect()
            ->route('courses.show', ['locale' => $locale, 'slug' => $course->slug])
            ->with('success', __('courses.enrolled_success'));
    }
}
