<?php

namespace App\Modules\Research\Livewire;

use App\Modules\Category\Models\Category;
use App\Modules\Research\Repositories\ResearchPaperRepositoryInterface;
use App\Support\Enums\CategoryType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.public')]
#[Title('Research')]
class ResearchIndex extends Component
{
    use WithPagination;

    #[Url]
    public ?int $category = null;

    #[Url]
    public string $search = '';

    public function updating(): void
    {
        $this->resetPage();
    }

    public function render(ResearchPaperRepositoryInterface $repository)
    {
        return view('livewire.research.research-index', [
            'papers' => $repository->paginatePublished($this->category, $this->search ?: null),
            'categories' => Category::where('type', CategoryType::RESEARCH)->get(),
        ]);
    }
}
