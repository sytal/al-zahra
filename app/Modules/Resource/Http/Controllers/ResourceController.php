<?php

namespace App\Modules\Resource\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Resource\Repositories\ResourceRepositoryInterface;
use App\Modules\Resource\Services\ResourceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function __construct(
        private readonly ResourceRepositoryInterface $repository,
        private readonly ResourceService $service,
    ) {}

    public function show(Request $request, string $locale, string $slug): View
    {
        $resource = $this->repository->findPublishedBySlug($slug) ?? abort(404);
        $related = $this->repository->relatedTo($resource);

        $seo = [
            'title' => $resource->meta_title ?: $resource->title,
            'description' => $resource->meta_description ?: $resource->description,
            'image' => $resource->getFirstMediaUrl('thumbnail') ?: null,
            'type' => 'website',
            'schema' => null,
        ];

        return view('resources.show', compact('resource', 'related', 'seo'));
    }

    public function download(Request $request, string $locale, string $slug): RedirectResponse
    {
        $resource = $this->repository->findPublishedBySlug($slug) ?? abort(404);

        if (! $resource->is_free) {
            return back()->with('error', __('resources.paid_coming_soon'));
        }

        if (! $resource->hasMedia('resource_file')) {
            abort(404);
        }

        $this->service->recordDownload($resource);

        return redirect()->away($resource->getFirstMediaUrl('resource_file'));
    }
}
