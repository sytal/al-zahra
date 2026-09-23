<div class="mx-auto max-w-6xl px-4 py-10" data-aos="fade-up">
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-ink">{{ __('research.page_title') }}</h1>
        <p class="mt-2 text-ink/60">{{ __('research.page_intro') }}</p>
    </header>

    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end">
        <x-select name="category" :label="__('research.filter_category')" wire:model.live="category" class="sm:w-56">
            <option value="">{{ __('research.all_categories') }}</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </x-select>

        <x-input name="search" :label="__('research.search_label')" wire:model.live.debounce.400ms="search" class="sm:flex-1" />
    </div>

    @if ($papers->isEmpty())
        <x-empty-state icon="beaker" :title="__('research.empty_title')" :message="__('research.empty_message')" />
    @else
        <div class="grid gap-6 sm:grid-cols-2">
            @foreach ($papers as $paper)
                <x-card hoverable class="flex gap-4">
                    <a href="{{ route('research.show', ['locale' => app()->getLocale(), 'slug' => $paper->slug]) }}" wire:navigate class="flex gap-4">
                        <img
                            src="{{ $paper->getFirstMediaUrl('cover_image') ?: asset('images/research-placeholder.png') }}"
                            alt="{{ $paper->title }}"
                            class="size-24 shrink-0 rounded-lg object-cover"
                        >
                        <div>
                            <div class="mb-1 flex flex-wrap gap-1.5">
                                @if ($paper->category)
                                    <x-badge color="brand" :text="$paper->category->name" />
                                @endif
                                @if ($paper->published_year)
                                    <x-badge color="neutral" :text="(string) $paper->published_year" />
                                @endif
                            </div>
                            <h2 class="font-semibold text-ink">{{ $paper->title }}</h2>
                            <p class="mt-1 text-sm text-ink/60">{{ \Illuminate\Support\Str::limit($paper->findings_summary, 100) }}</p>
                        </div>
                    </a>
                </x-card>
            @endforeach
        </div>

        <x-pagination :paginator="$papers" />
    @endif
</div>
