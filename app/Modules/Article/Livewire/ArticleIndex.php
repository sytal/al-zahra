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
        return view('livewire.article.article-index', [
            'articles' => $repository->paginatePublished($this->category, $this->search ?: null),
            'categories' => Category::where('type', CategoryType::ARTICLE)->get(),
        ]);
    }
}
