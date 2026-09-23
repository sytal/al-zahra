<?php

namespace App\Modules\Course\Livewire;

use App\Modules\Course\Repositories\CourseRepositoryInterface;
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

        return view('livewire.course.course-index', [
            'courses' => $repository->paginatePublished(
                audience: $this->audience ?: null,
                level: $this->level ?: null,
                isFree: $isFree,
                search: $this->search ?: null,
            ),
        ]);
    }
}
