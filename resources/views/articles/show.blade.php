<x-layouts.public>
    <x-slot name="seo">
        <x-seo :title="$article->title" :description="$article->excerpt" :image="$article->getFirstMediaUrl('featured_image', 'hero')" type="article" />
    </x-slot>

    <x-detail-layout :title="$article->title">
        <x-slot name="meta">
            <x-breadcrumbs :items="[
                ['label' => __('nav.articles'), 'url' => route('articles.index', app()->getLocale())],
                ['label' => $article->title],
            ]" />
            <span>{{ $article->author->name }}</span>
            <span>&middot;</span>
            <span>{{ $article->published_at?->format('M d, Y') }}</span>
            <span>&middot;</span>
            <span>{{ $article->reading_time_minutes }} {{ __('articles.min_read') }}</span>
            @if ($article->category)
                <x-badge color="brand" :text="$article->category->name" />
            @endif
        </x-slot>

        @if ($article->hasMedia('featured_image'))
            <img src="{{ $article->getFirstMediaUrl('featured_image', 'hero') }}" alt="{{ $article->title }}" class="mb-8 w-full rounded-xl">
        @endif

        {!! $article->body !!}

        @if ($article->tags->isNotEmpty())
            <div class="mt-8 flex flex-wrap gap-2">
                @foreach ($article->tags as $tag)
                    <x-badge color="neutral" :text="$tag->name" />
                @endforeach
            </div>
        @endif

        <x-slot name="share">
            <x-share-buttons :url="url()->current()" :title="$article->title" />
        </x-slot>

        <x-slot name="related">
            @if ($related->isNotEmpty())
                <h2 class="mb-4 text-xl font-semibold text-ink">{{ __('articles.related_title') }}</h2>
                <div class="grid gap-6 sm:grid-cols-3">
                    @foreach ($related as $item)
                        <x-card hoverable>
                            <a href="{{ route('articles.show', ['locale' => app()->getLocale(), 'slug' => $item->slug]) }}" wire:navigate>
                                <h3 class="font-medium text-ink">{{ $item->title }}</h3>
                            </a>
                        </x-card>
                    @endforeach
                </div>
            @endif
        </x-slot>
    </x-detail-layout>
</x-layouts.public>
