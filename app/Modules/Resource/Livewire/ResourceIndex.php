<?php

namespace App\Modules\Resource\Livewire;

use App\Modules\Category\Models\Category;
use App\Modules\Resource\Repositories\ResourceRepositoryInterface;
use App\Support\Enums\CategoryType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.public')]
#[Title('Resources')]
class ResourceIndex extends Component
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

    public function render(ResourceRepositoryInterface $repository)
    {
        return view('livewire.resource.resource-index', [
            'resources' => $repository->paginatePublished($this->category, $this->search ?: null),
            'categories' => Category::where('type', CategoryType::RESOURCE)->get(),
        ]);
    }
}
