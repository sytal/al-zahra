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

    #[Url]
    public ?int $year = null;

    public function updating(): void
    {
        $this->resetPage();
    }

    public function render(ResearchPaperRepositoryInterface $repository)
    {
        $stats = $repository->listStats();
        $counts = $stats['categories'];

        $categories = Category::where('type', CategoryType::RESEARCH)->get();
        $chips = $categories->map(fn (Category $cat) => [
            'value' => $cat->id,
            'label' => $cat->name,
            'count' => (int) ($counts[$cat->id] ?? 0),
        ])->all();

        return view('livewire.research.research-index', [
            'papers' => $repository->paginatePublished($this->category, $this->search ?: null, 12, $this->year),
            'categories' => $categories,
            'chips' => $chips,
            'years' => $stats['years'],
            'stats' => $stats,
        ]);
    }
}
