<x-layouts.public>
    <x-slot name="seo">
        <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" />
    </x-slot>

    <x-detail-layout :title="$paper->title">
        <x-slot name="meta">
            <x-breadcrumbs :items="[
                ['label' => __('nav.research'), 'url' => route('research.index', app()->getLocale())],
                ['label' => $paper->title],
            ]" />
            @if (!empty($paper->co_authors))
                <span>{{ implode(', ', $paper->co_authors) }}</span>
            @endif
            @if ($paper->published_year)
                <x-badge color="neutral" :text="(string) $paper->published_year" />
            @endif
            @if ($paper->category)
                <x-badge color="brand" :text="$paper->category->name" />
            @endif
        </x-slot>

        @if ($paper->hasMedia('cover_image'))
            <img src="{{ $paper->getFirstMediaUrl('cover_image') }}" alt="{{ $paper->title }}" class="mb-8 w-full rounded-xl">
        @endif

        @if ($paper->research_question)
            <div class="mb-6 rounded-xl border border-ink/10 bg-surface p-5">
                <h2 class="font-semibold text-ink">{{ __('research.research_question') }}</h2>
                <p class="mt-2 text-ink/80">{{ $paper->research_question }}</p>
            </div>
        @endif

        @if ($paper->findings_summary)
            <div class="mb-6 rounded-xl border border-ink/10 bg-surface p-5">
                <h2 class="font-semibold text-ink">{{ __('research.what_they_found') }}</h2>
                <p class="mt-2 text-ink/80">{{ $paper->findings_summary }}</p>
            </div>
        @endif

        @if ($paper->significance)
            <div class="mb-6 rounded-xl border border-ink/10 bg-surface p-5">
                <h2 class="font-semibold text-ink">{{ __('research.why_it_matters') }}</h2>
                <p class="mt-2 text-ink/80">{{ $paper->significance }}</p>
            </div>
        @endif

        @if ($paper->methodology_summary)
            <x-accordion :items="[
                ['title' => __('research.methodology'), 'content' => $paper->methodology_summary],
            ]" />
        @endif

        <div class="mt-8">
            @if ($paper->full_paper_type === \App\Support\Enums\FullPaperType::EXTERNAL_LINK && $paper->external_url)
                <x-button :href="$paper->external_url" variant="primary" icon="arrow-top-right-on-square">{{ __('research.read_full_paper') }}</x-button>
            @elseif ($paper->hasMedia('paper_file'))
                <x-button :href="$paper->getFirstMediaUrl('paper_file')" variant="primary" icon="document-arrow-down">{{ __('research.read_full_paper') }}</x-button>
            @endif
        </div>

        <x-slot name="share">
            <x-share-buttons :url="url()->current()" :title="$paper->title" />
        </x-slot>

        <x-slot name="related">
            @if ($related->isNotEmpty())
                <h2 class="mb-4 text-xl font-semibold text-ink">{{ __('research.related_title') }}</h2>
                <div class="grid gap-6 sm:grid-cols-3">
                    @foreach ($related as $item)
                        <x-card hoverable>
                            <a href="{{ route('research.show', ['locale' => app()->getLocale(), 'slug' => $item->slug]) }}" wire:navigate>
                                <h3 class="font-medium text-ink">{{ $item->title }}</h3>
                            </a>
                        </x-card>
                    @endforeach
                </div>
            @endif
        </x-slot>
    </x-detail-layout>
</x-layouts.public>
