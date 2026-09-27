<?php

namespace App\Modules\Resource\Livewire;

use App\Modules\Category\Models\Category;
use App\Modules\Resource\Repositories\ResourceRepositoryInterface;
use App\Support\Enums\CategoryType;
use App\Support\Enums\ResourceType;
use App\Support\ListSeo;
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

    #[Url]
    public string $type = '';

    #[Url]
    public string $pricing = '';

    public function updating(): void
    {
        $this->resetPage();
    }

    public function render(ResourceRepositoryInterface $repository)
    {
        $stats = $repository->listStats();
        $counts = $stats['categories'];

        $type = ResourceType::tryFrom($this->type)?->value;
        $pricing = in_array($this->pricing, ['free', 'paid'], true) ? $this->pricing : null;

        $categories = Category::where('type', CategoryType::RESOURCE)->get();
        $chips = $categories->map(fn (Category $cat) => [
            'value' => $cat->id,
            'label' => $cat->name,
            'count' => (int) ($counts[$cat->id] ?? 0),
        ])->all();

        $list = $repository->paginatePublished($this->category, $this->search ?: null, 12, $type, $pricing);
        $seoHead = ListSeo::head(
            __('lists_ui.seo_rc_title'),
            __('lists_ui.seo_rc_desc'),
            $list->first()?->getFirstMediaUrl('thumbnail') ?: null,
            $list->getCollection()->map(fn ($item) => ['name' => (string) $item->title, 'url' => route('resources.show', ['locale' => app()->getLocale(), 'slug' => $item->slug])]),
            $list->currentPage(),
        );

        return view('livewire.resource.resource-index', [
            'resources' => $list,
            'categories' => $categories,
            'chips' => $chips,
            'types' => $stats['types'],
            'stats' => $stats,
            'activeType' => $type,
            'activePricing' => $pricing,
        ])
            ->title(__('lists_ui.seo_rc_title'))
            ->layoutData(['seo' => $seoHead]);
    }
}
