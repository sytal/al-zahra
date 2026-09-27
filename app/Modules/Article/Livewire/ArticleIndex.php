<?php

namespace App\Modules\Article\Livewire;

use App\Modules\Article\Repositories\ArticleRepositoryInterface;
use App\Modules\Category\Models\Category;
use App\Support\Enums\CategoryType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.public')]
#[Title('Articles')]
class ArticleIndex extends Component
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

    public function render(ArticleRepositoryInterface $repository)
    {
        $stats = $repository->listStats();
        $counts = $stats['categories'];

        $categories = Category::where('type', CategoryType::ARTICLE)->get();
        $chips = $categories->map(fn (Category $cat) => [
            'value' => $cat->id,
            'label' => $cat->name,
            'count' => (int) ($counts[$cat->id] ?? 0),
        ])->all();

        return view('livewire.article.article-index', [
            'articles' => $repository->paginatePublished($this->category, $this->search ?: null),
            'categories' => $categories,
            'chips' => $chips,
            'stats' => $stats,
        ]);
    }
}
