@php
    $hasFilter = $category || $search !== '';
    $showFeatured = ! $hasFilter && $articles->currentPage() === 1 && $articles->count() > 1;
    $featured = $showFeatured ? $articles->first() : null;
    $rest = $showFeatured ? $articles->slice(1) : $articles;
    $grid = 'grid gap-5 sm:grid-cols-2 lg:grid-cols-3 lg:gap-6';
@endphp

<div>
    <section class="relative isolate overflow-hidden border-b bg-hero-gradient">
        <span class="glow-teal -top-32 start-[-6rem] opacity-80" style="--glow-size: 26rem" aria-hidden="true"></span>
        <span class="glow-gold bottom-[-9rem] end-[-5rem] opacity-60" style="--glow-size: 22rem" aria-hidden="true"></span>
        <span class="bg-pattern-islamic pointer-events-none absolute inset-0 -z-10" aria-hidden="true"></span>

        <div class="container-page grid items-center gap-8 pb-8 pt-10 sm:pb-10 sm:pt-14 md:grid-cols-12 lg:pb-12 lg:pt-20">
            <div class="min-w-0 md:col-span-7">
                <p class="eyebrow mb-4 flex items-center gap-2"><span class="star-mark text-sm" aria-hidden="true"></span>{{ __('lists_ui.art_eyebrow') }}</p>
                <h1 class="heading-display break-words">{{ __('articles.page_title') }}</h1>
                <p class="lead mt-4 max-w-xl">{{ __('articles.page_intro') }}</p>

                <div class="mt-8 flex flex-wrap items-end gap-x-8 gap-y-4">
                    <p class="flex items-end gap-3">
                        <span class="numeral-display text-gradient-brand" x-data="counter({ to: {{ (int) $stats['total'] }} })" x-text="display">{{ (int) $stats['total'] }}</span>
                        <span class="pb-2 text-sm font-semibold text-body">{{ trans_choice('lists_ui.art_count', (int) $stats['total']) }}</span>
                    </p>
                    @if (count($chips))
                        <p class="flex items-center gap-2 pb-2 text-sm text-body">
                            <span class="star-mark text-xs" aria-hidden="true"></span>{{ trans_choice('lists_ui.art_topics', count($chips), ['count' => count($chips)]) }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="relative hidden md:col-span-5 md:block">
                <span class="star-mark absolute -start-6 -top-6 z-0 text-8xl opacity-25 motion-safe:animate-float" aria-hidden="true"></span>
                <div class="glass relative z-raised rotate-1 rounded-panel border p-4 shadow-float">
                    <img src="{{ asset('images/illustrations/illus-articles.svg') }}" alt="" width="400" height="300" decoding="async" fetchpriority="high" class="h-auto w-full">
                </div>
            </div>
        </div>
        <span class="gold-thread absolute inset-x-0 bottom-0" aria-hidden="true"></span>
    </section>

    <x-section variant="light" tight id="articles-results" class="!pt-5 sm:!pt-6">
        <x-filter-bar sticky :chips="$chips" chips-model="category" :selected="$category ?? ''" search-model="search" :search="$search"
            :search-label="__('articles.search_label')" :placeholder="__('lists_ui.art_search')" :all-label="__('lists_ui.art_all')"
            :chips-label="__('lists_ui.art_chips')" :count="$articles->total()" />

        <div class="mt-8">
            <div wire:loading.delay.shorter.grid class="hidden {{ $grid }}" aria-hidden="true">
                @foreach (range(1, 6) as $i)
                    <x-article-card skeleton />
                @endforeach
            </div>

            <div wire:loading.delay.shorter.remove>
                @if ($articles->isEmpty())
                    <x-empty-state icon="document-text" illustration="articles" :title="__('articles.empty_title')" :message="__('articles.empty_message')">
                        <x-slot:action>
                            <x-button type="button" variant="outline" icon="x-mark" x-on:click="$wire.set('category', ''); $wire.set('search', '')">{{ __('lists_ui.clear') }}</x-button>
                        </x-slot:action>
                    </x-empty-state>
                @else
                    @if ($featured)
                        <div class="mb-6 lg:mb-8">
                            <x-article-card :article="$featured" variant="featured" as="h2" />
                        </div>
                        <p class="mb-5 flex items-center gap-3 text-sm font-semibold text-strong">
                            <span class="star-mark text-xs" aria-hidden="true"></span>{{ __('lists_ui.art_more') }}
                            <span class="gold-thread h-px flex-1" aria-hidden="true"></span>
                        </p>
                    @endif

                    <div class="stagger {{ $grid }}">
                        @foreach ($rest as $article)
                            <x-article-card :article="$article" as="h2" wire:key="article-{{ $article->id }}" />
                        @endforeach
                    </div>

                    <x-pagination :paginator="$articles" />
                @endif
            </div>
        </div>
    </x-section>
</div>
