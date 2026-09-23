<div class="mx-auto max-w-6xl px-4 py-10" data-aos="fade-up">
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-ink">{{ __('resources.page_title') }}</h1>
        <p class="mt-2 text-ink/60">{{ __('resources.page_intro') }}</p>
    </header>

    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end">
        <x-select name="category" :label="__('resources.filter_category')" wire:model.live="category" class="sm:w-56">
            <option value="">{{ __('resources.all_categories') }}</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </x-select>

        <x-input name="search" :label="__('resources.search_label')" wire:model.live.debounce.400ms="search" class="sm:flex-1" />
    </div>

    @if ($resources->isEmpty())
        <x-empty-state icon="folder" :title="__('resources.empty_title')" :message="__('resources.empty_message')" />
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($resources as $resource)
                <x-card hoverable class="flex flex-col overflow-hidden !p-0">
                    <a href="{{ route('resources.show', ['locale' => app()->getLocale(), 'slug' => $resource->slug]) }}" wire:navigate>
                        <img
                            src="{{ $resource->getFirstMediaUrl('thumbnail') ?: asset('images/resource-placeholder.png') }}"
                            alt="{{ $resource->title }}"
                            class="aspect-video w-full object-cover"
                        >
                        <div class="p-5">
                            <div class="mb-2 flex flex-wrap gap-1.5">
                                <x-badge color="brand" :text="ucfirst($resource->resource_type->value)" />
                                <x-badge :color="$resource->is_free ? 'success' : 'warning'" :text="$resource->is_free ? __('common.free') : __('common.paid')" />
                            </div>
                            <h2 class="font-semibold text-ink">{{ $resource->title }}</h2>
                            <p class="mt-1 text-sm text-ink/60">{{ $resource->description }}</p>
                        </div>
                    </a>
                </x-card>
            @endforeach
        </div>

        <x-pagination :paginator="$resources" />
    @endif
</div>
