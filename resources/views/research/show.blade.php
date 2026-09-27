@php
    $locale = app()->getLocale();
    $cover = $paper->getFirstMediaUrl('cover_image');
    $coverFallback = asset('images/research-placeholder.svg');
    $authors = collect($paper->co_authors ?? [])->filter()->values();
    $isExternal = $paper->full_paper_type === \App\Support\Enums\FullPaperType::EXTERNAL_LINK && $paper->external_url;
    $paperUrl = $isExternal ? $paper->external_url : ($paper->hasMedia('paper_file') ? $paper->getFirstMediaUrl('paper_file') : null);
    $sections = [
        ['field' => 'research_question', 'label' => __('research.research_question'), 'icon' => 'magnifying-glass'],
        ['field' => 'findings_summary', 'label' => __('research.what_they_found'), 'icon' => 'light-bulb'],
        ['field' => 'significance', 'label' => __('research.why_it_matters'), 'icon' => 'sparkles'],
    ];
    $sections = array_values(array_filter($sections, fn ($s) => filled($paper->{$s['field']})));
    $ctaClasses = 'group shimmer-sweep focus-ring inline-flex min-h-12 w-full min-w-0 items-center justify-center gap-3 rounded-xl bg-brand-primary px-6 py-3 text-base font-semibold text-on-brand shadow-soft transition duration-fast ease-enter active:scale-[0.98] [@media(hover:hover)]:hover:bg-brand-hover [@media(hover:hover)]:hover:shadow-lift';
@endphp
<x-layouts.public>
    <x-slot name="seo">
        <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" />
    </x-slot>

    <article class="min-w-0">
        <header class="bg-section-pattern relative isolate overflow-hidden">
            <span class="glow-teal -z-10" style="inset-inline-end:-8rem;top:-8rem;--glow-size:26rem" aria-hidden="true"></span>
            <div class="container-page py-8 sm:py-12 lg:py-16">
                <x-breadcrumbs :items="[
                    ['label' => __('nav.research'), 'url' => route('research.index', $locale)],
                    ['label' => $paper->title],
                ]" />

                <div class="grid min-w-0 items-start gap-8 lg:grid-cols-[minmax(0,1fr)_14rem] lg:gap-14">
                    <div class="card-surface relative min-w-0 overflow-hidden border-s-4 border-s-[var(--color-brand-secondary)] p-6 sm:p-9" data-aos="fade-up">
                        <span class="pointer-events-none absolute end-0 top-0 size-12 bg-surface-sunken [clip-path:polygon(0_0,100%_0,100%_100%)] rtl:-scale-x-100" aria-hidden="true"></span>
                        <p class="flex flex-wrap items-center gap-x-3 gap-y-2 text-sm font-semibold text-secondary-text">
                            <span class="inline-flex items-center gap-2"><x-icon name="beaker" class="size-5" aria-hidden="true" />{{ __('detail_ui.research_paper') }}</span>
                            @if ($paper->category)<x-badge color="brand" :text="$paper->category->name" />@endif
                            @if ($paper->published_year)<x-badge color="neutral" variant="outline" :text="(string) $paper->published_year" />@endif
                        </p>
                        <h1 class="heading-1 mt-4 break-words [text-wrap:balance]">{{ $paper->title }}</h1>
                        <span class="gold-thread mt-6 block max-w-xs" aria-hidden="true"></span>
                        @if ($authors->isNotEmpty())
                            <p class="mt-5 flex flex-wrap items-baseline gap-x-2 text-body">
                                <span class="text-sm font-semibold text-muted">{{ __('detail_ui.authors') }}:</span>
                                <span class="min-w-0 break-words font-display text-lg font-semibold text-strong">{{ $authors->implode(', ') }}</span>
                            </p>
                        @endif
                    </div>

                    <div class="hidden justify-self-center lg:block" aria-hidden="true">
                        <div class="tilt relative h-64 w-44 overflow-hidden rounded-md border bg-surface-sunken shadow-float" x-data="tilt">
                            <img src="{{ $cover ?: $coverFallback }}" alt="" width="176" height="256" decoding="async" onerror="this.onerror=null;this.src='{{ $coverFallback }}'" class="size-full object-cover dark:brightness-90">
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-page py-10 sm:py-14">
            <div class="grid min-w-0 gap-10 lg:grid-cols-[minmax(0,1fr)_20rem] lg:gap-14">
                <div class="min-w-0 space-y-6">
                    @forelse ($sections as $i => $s)
                        <section class="card-surface relative min-w-0 overflow-hidden p-6 sm:p-8" data-aos="fade-up" aria-labelledby="sec-{{ $i }}">
                            <span class="numeral-display pointer-events-none absolute -top-2 end-4 select-none text-7xl text-strong opacity-[0.06]" aria-hidden="true">{{ $i + 1 }}</span>
                            <div class="relative flex items-start gap-4">
                                <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-tint text-brand-primary"><x-icon :name="$s['icon']" class="size-6" aria-hidden="true" /></span>
                                <div class="min-w-0">
                                    <h2 id="sec-{{ $i }}" class="heading-3 break-words">{{ $s['label'] }}</h2>
                                    <p class="mt-3 whitespace-pre-line break-words text-base text-body sm:text-lg">{{ $paper->{$s['field']} }}</p>
                                </div>
                            </div>
                        </section>
                    @empty
                        <x-empty-state icon="document-text" :title="__('detail_ui.empty_body_title')" :message="__('detail_ui.empty_body')" compact />
                    @endforelse

                    @if ($paper->methodology_summary)
                        <div data-aos="fade-up">
                            <x-accordion :items="[['title' => __('research.methodology'), 'content' => $paper->methodology_summary]]" />
                        </div>
                    @endif
                </div>

                <aside class="min-w-0 space-y-6 lg:sticky lg:top-28 lg:self-start" aria-label="{{ __('detail_ui.full_paper') }}">
                    <div class="gradient-border-gold rounded-panel">
                        <div class="rounded-panel bg-surface-raised p-5 sm:p-6">
                            <p class="eyebrow flex items-center gap-2 text-secondary-text"><span class="star-mark text-xs" aria-hidden="true"></span>{{ __('detail_ui.full_paper') }}</p>
                            @if ($paperUrl)
                                <a href="{{ $paperUrl }}" target="_blank" rel="noopener noreferrer" class="{{ $ctaClasses }} mt-4">
                                    <x-icon :name="$isExternal ? 'arrow-top-right-on-square' : 'document-arrow-down'" class="size-5 shrink-0" aria-hidden="true" />
                                    <span class="min-w-0 truncate">{{ __('research.read_full_paper') }}</span>
                                </a>
                                <p class="mt-3 text-xs text-muted">{{ $isExternal ? __('detail_ui.opens_external') : __('detail_ui.opens_pdf') }}</p>
                            @else
                                <p class="mt-3 flex items-start gap-2 text-sm text-body"><x-icon name="no-symbol" class="mt-0.5 size-5 shrink-0 text-muted" aria-hidden="true" />{{ __('detail_ui.paper_unavailable') }}</p>
                            @endif
                        </div>
                    </div>

                    <x-cite-box :authors="$authors->all()" :year="$paper->published_year" :title="$paper->title" :source="config('app.name')" />

                    <div class="card-surface p-5">
                        <x-share-buttons :url="url()->current()" :title="$paper->title" />
                    </div>
                </aside>
            </div>
        </div>

        @if ($related->isNotEmpty())
            <section class="bg-section-sunken section" aria-labelledby="related-title">
                <div class="container-page">
                    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
                        <h2 id="related-title" class="heading-2 min-w-0 break-words">{{ __('research.related_title') }}</h2>
                        <a href="{{ route('research.index', $locale) }}" wire:navigate class="link-underline focus-ring inline-flex min-h-11 items-center gap-1.5 text-sm font-semibold text-brand-primary">
                            {{ __('detail_ui.back_research') }}
                            <x-icon name="arrow-right" class="size-4 rtl:-scale-x-100" aria-hidden="true" />
                        </a>
                    </div>
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($related as $item)
                            <div data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}"><x-research-card :paper="$item" as="h3" /></div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </article>
</x-layouts.public>
