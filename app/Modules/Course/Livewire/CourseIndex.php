<?php

namespace App\Modules\Course\Livewire;

use App\Modules\Course\Repositories\CourseRepositoryInterface;
use App\Support\ListSeo;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.public')]
#[Title('Courses')]
class CourseIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $audience = '';

    #[Url]
    public string $level = '';

    #[Url]
    public string $pricing = '';

    #[Url]
    public string $search = '';

    public function updating(): void
    {
        $this->resetPage();
    }

    public function render(CourseRepositoryInterface $repository)
    {
        $isFree = match ($this->pricing) {
            'free' => true,
            'paid' => false,
            default => null,
        };

        $courses = $repository->paginatePublished(
            audience: $this->audience ?: null,
            level: $this->level ?: null,
            isFree: $isFree,
            search: $this->search ?: null,
        );

        $seoHead = ListSeo::head(
            __('courses_ui.seo_title'),
            __('courses_ui.seo_desc'),
            $courses->first()?->getFirstMediaUrl('cover_image', 'hero') ?: null,
            $courses->getCollection()->map(fn ($item) => ['name' => (string) $item->title, 'url' => route('courses.show', ['locale' => app()->getLocale(), 'slug' => $item->slug])]),
            $courses->currentPage(),
        );

        return view('livewire.course.course-index', ['courses' => $courses])
            ->title(__('courses_ui.seo_title'))
            ->layoutData(['seo' => $seoHead]);
    }
}
