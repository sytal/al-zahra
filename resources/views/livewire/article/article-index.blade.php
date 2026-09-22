<div class="mx-auto max-w-6xl px-4 py-10" data-aos="fade-up">
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-ink">{{ __('articles.page_title') }}</h1>
        <p class="mt-2 text-ink/60">{{ __('articles.page_intro') }}</p>
    </header>

    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end">
        <x-select name="category" :label="__('articles.filter_category')" wire:model.live="category" class="sm:w-56">
            <option value="">{{ __('articles.all_categories') }}</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </x-select>

        <x-input name="search" :label="__('articles.search_label')" wire:model.live.debounce.400ms="search" class="sm:flex-1" />
    </div>

    @if ($articles->isEmpty())
        <x-empty-state icon="document-text" :title="__('articles.empty_title')" :message="__('articles.empty_message')" />
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($articles as $article)
                <x-card hoverable class="flex flex-col overflow-hidden !p-0">
                    <a href="{{ route('articles.show', ['locale' => app()->getLocale(), 'slug' => $article->slug]) }}" wire:navigate>
                        <img
                            src="{{ $article->getFirstMediaUrl('featured_image', 'card') ?: asset('images/article-placeholder.png') }}"
                            alt="{{ $article->title }}"
                            class="aspect-video w-full object-cover"
                        >
                        <div class="p-5">
                            @if ($article->category)
                                <x-badge color="brand" :text="$article->category->name" class="mb-2" />
                            @endif
                            <h2 class="font-semibold text-ink">{{ $article->title }}</h2>
                            <p class="mt-1 text-sm text-ink/60">{{ $article->excerpt }}</p>
                            <p class="mt-3 text-xs text-ink/40">{{ $article->reading_time_minutes }} {{ __('articles.min_read') }}</p>
                        </div>
                    </a>
                </x-card>
            @endforeach
        </div>

        <x-pagination :paginator="$articles" />
    @endif
</div>
