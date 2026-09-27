@php
    $locale = app()->getLocale();
    $hero = $article->getFirstMediaUrl('featured_image', 'hero');
    $hasBody = trim(strip_tags((string) $article->body)) !== '';
    $date = $article->published_at;
@endphp
<x-layouts.public>
    <x-slot name="seo">
        <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" />
    </x-slot>

    <style>
        @media print {
            [data-print-hide], .print-hide { display: none !important; }
            [data-article] { padding: 0 !important; }
            [data-article] .prose-content a { text-decoration: none; color: inherit; }
            [data-article] .prose-content a[href^="http"]::after { content: " (" attr(href) ")"; font-size: 0.8em; word-break: break-all; }
            [data-article] img { break-inside: avoid; }
        }
    </style>

    <x-reading-progress target="[data-article-body]" class="print-hide" />

    <article data-article class="min-w-0">
        <header data-keep class="bg-section-tinted relative isolate overflow-hidden">
            <span class="glow-teal -z-10" style="inset-inline-start:-8rem;top:-6rem;--glow-size:26rem" aria-hidden="true"></span>
            <span class="glow-gold -z-10" style="inset-inline-end:-6rem;bottom:-8rem;--glow-size:22rem" aria-hidden="true"></span>
            <span class="bg-pattern-islamic absolute inset-0 -z-10 print:hidden" aria-hidden="true"></span>

            <div class="container-page py-8 sm:py-12 lg:py-16">
                <x-breadcrumbs class="print-hide" :items="[
                    ['label' => __('nav.articles'), 'url' => route('articles.index', $locale)],
                    ['label' => $article->title],
                ]" />

                <div class="mx-auto max-w-3xl text-center" data-aos="fade-up">
                    @if ($article->category)
                        <p class="mb-4 inline-flex max-w-full items-center gap-2 rounded-full border border-dashed border-strong bg-surface-raised px-4 py-1.5 text-sm font-semibold text-secondary-text">
                            <span class="star-mark text-xs" aria-hidden="true"></span>
                            <span class="min-w-0 truncate">{{ $article->category->name }}</span>
                        </p>
                    @endif

                    <h1 class="heading-display break-words [text-wrap:balance]">{{ $article->title }}</h1>

                    @if ($article->excerpt)
                        <p class="lead mx-auto mt-5 max-w-2xl break-words text-body">{{ $article->excerpt }}</p>
                    @endif

                    <div class="mt-7 flex flex-wrap items-center justify-center gap-x-5 gap-y-3 text-sm text-body">
                        @if ($article->author)
                            <span class="inline-flex min-w-0 items-center gap-3">
                                <x-avatar :name="$article->author->name" size="md" ringed />
                                <span class="min-w-0 text-start leading-tight">
                                    <span class="block text-xs text-muted">{{ __('detail_ui.written_by') }}</span>
                                    <span class="block max-w-[14rem] truncate font-semibold text-strong">{{ $article->author->name }}</span>
                                </span>
                            </span>
                        @endif
                        @if ($date)
                            <span class="inline-flex items-center gap-1.5"><x-icon name="calendar-days" class="size-4 text-secondary-text" aria-hidden="true" /><time datetime="{{ $date->toDateString() }}">{{ $date->translatedFormat('j F Y') }}</time></span>
                        @endif
                        @if ($article->reading_time_minutes)
                            <span class="inline-flex items-center gap-1.5"><x-icon name="clock" class="size-4 text-secondary-text" aria-hidden="true" />{{ $article->reading_time_minutes }} {{ __('articles.min_read') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        @if ($hero)
            <div class="container-page -mt-6 sm:-mt-10 lg:-mt-14" data-aos="fade-up" data-aos-delay="80">
                <figure class="relative mx-auto max-w-5xl">
                    <div class="gradient-border-gold overflow-hidden rounded-panel shadow-lift">
                        <img src="{{ $hero }}" alt="{{ $article->title }}" width="1200" height="630" decoding="async" fetchpriority="high"
                            class="aspect-[1200/630] w-full bg-surface-sunken object-cover dark:brightness-90"
                            onerror="this.closest('figure').remove()">
                    </div>
                </figure>
            </div>
        @endif

        <div class="container-page py-10 sm:py-14">
            <div class="mx-auto grid min-w-0 max-w-[76rem] gap-8 lg:grid-cols-[4.5rem_minmax(0,1fr)_15rem] lg:gap-10 xl:grid-cols-[4.5rem_minmax(0,1fr)_17rem] xl:gap-14">
                <aside class="print-hide hidden lg:block" aria-label="{{ __('kit_sections.share') }}">
                    <div class="sticky top-28 flex flex-col items-center gap-3">
                        <x-share-buttons :url="url()->current()" :title="$article->title" direction="col" class="!flex-col !items-center [&>span:first-child]:text-xs [&>span:first-child]:text-muted" />
                        <button type="button" onclick="window.print()" class="focus-ring tap-target size-11 rounded-full border bg-surface-raised text-body transition duration-fast ease-enter active:scale-95 [@media(hover:hover)]:hover:-translate-y-0.5 [@media(hover:hover)]:hover:border-brand-primary [@media(hover:hover)]:hover:text-brand-primary" title="{{ __('detail_ui.print') }}" aria-label="{{ __('detail_ui.print') }}">
                            <x-icon name="printer" class="size-5" aria-hidden="true" />
                        </button>
                    </div>
                </aside>

                <div class="min-w-0">
                    <x-toc target="[data-article-body]" class="print-hide mb-8 lg:hidden" />

                    @if ($hasBody)
                        <div data-article-body class="prose-content mx-auto max-w-[44rem] min-w-0 break-words text-body lg:mx-0 lg:max-w-none">
                            {!! $article->body !!}
                        </div>
                    @else
                        <div data-article-body>
                            <x-empty-state icon="document-text" :title="__('detail_ui.empty_body_title')" :message="__('detail_ui.empty_body')" compact />
                        </div>
                    @endif

                    <div class="star-divider mt-12" aria-hidden="true"><span class="star-mark"></span></div>

                    @if ($article->tags->isNotEmpty())
                        <div class="mt-8">
                            <h2 class="mb-3 text-sm font-semibold text-strong">{{ __('detail_ui.tags') }}</h2>
                            <ul class="flex flex-wrap gap-2">
                                @foreach ($article->tags as $tag)
                                    <li class="min-w-0"><x-tag-pill :text="$tag->name" /></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="print-hide mt-8 flex flex-wrap items-center gap-3 lg:hidden">
                        <x-share-buttons :url="url()->current()" :title="$article->title" />
                        <button type="button" onclick="window.print()" class="focus-ring tap-target size-11 rounded-full border bg-surface-raised text-body active:scale-95" title="{{ __('detail_ui.print') }}" aria-label="{{ __('detail_ui.print') }}">
                            <x-icon name="printer" class="size-5" aria-hidden="true" />
                        </button>
                    </div>

                    @if ($article->author)
                        <section class="card-surface relative mt-10 overflow-hidden p-5 sm:p-7" aria-labelledby="author-box-title">
                            <span class="glow-gold -z-0 opacity-60" style="inset-inline-end:-5rem;top:-5rem;--glow-size:14rem" aria-hidden="true"></span>
                            <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-6">
                                <x-avatar :name="$article->author->name" size="xl" ringed class="mx-auto sm:mx-0" />
                                <div class="min-w-0 text-center sm:text-start">
                                    <p class="eyebrow text-secondary-text">{{ __('detail_ui.about_author') }}</p>
                                    <h2 id="author-box-title" class="heading-3 mt-1 break-words">{{ $article->author->name }}</h2>
                                    <p class="mt-2 text-sm text-body">{{ __('detail_ui.author_blurb') }}</p>
                                    <a href="{{ route('about', $locale) }}" wire:navigate class="link-underline focus-ring mt-3 inline-flex min-h-11 items-center gap-1.5 text-sm font-semibold text-brand-primary">
                                        {{ __('detail_ui.meet_institute') }}
                                        <x-icon name="arrow-right" class="size-4 rtl:-scale-x-100" aria-hidden="true" />
                                    </a>
                                </div>
                            </div>
                        </section>
                    @endif

                    @if ($previous || $next)
                        <nav class="print-hide mt-8 grid gap-4 sm:grid-cols-2" aria-label="{{ __('detail_ui.previous_article') }} / {{ __('detail_ui.next_article') }}">
                            @if ($previous)
                                <a href="{{ route('articles.show', ['locale' => $locale, 'slug' => $previous->slug]) }}" wire:navigate
                                    class="card-surface card-hover focus-ring group flex min-w-0 items-center gap-4 p-4 sm:p-5">
                                    <x-icon name="arrow-left" class="size-5 shrink-0 text-brand-primary transition-transform duration-base rtl:-scale-x-100 [@media(hover:hover)]:group-hover:-translate-x-1 rtl:[@media(hover:hover)]:group-hover:translate-x-1" aria-hidden="true" />
                                    <span class="min-w-0">
                                        <span class="block text-xs font-semibold text-muted">{{ __('detail_ui.previous_article') }}</span>
                                        <span class="mt-1 line-clamp-2 break-words font-display font-semibold leading-snug text-strong">{{ $previous->title }}</span>
                                    </span>
                                </a>
                            @else
                                <span class="hidden sm:block" aria-hidden="true"></span>
                            @endif
                            @if ($next)
                                <a href="{{ route('articles.show', ['locale' => $locale, 'slug' => $next->slug]) }}" wire:navigate
                                    class="card-surface card-hover focus-ring group flex min-w-0 items-center justify-between gap-4 p-4 sm:p-5 sm:text-end">
                                    <span class="min-w-0">
                                        <span class="block text-xs font-semibold text-muted">{{ __('detail_ui.next_article') }}</span>
                                        <span class="mt-1 line-clamp-2 break-words font-display font-semibold leading-snug text-strong">{{ $next->title }}</span>
                                    </span>
                                    <x-icon name="arrow-right" class="size-5 shrink-0 text-brand-primary transition-transform duration-base rtl:-scale-x-100 [@media(hover:hover)]:group-hover:translate-x-1 rtl:[@media(hover:hover)]:group-hover:-translate-x-1" aria-hidden="true" />
                                </a>
                            @endif
                        </nav>
                    @endif
                </div>

                <aside class="print-hide min-w-0 max-lg:hidden" aria-label="{{ __('kit_sections.article_tools') }}">
                    <div class="sticky top-28">
                        <x-toc target="[data-article-body]" />
                    </div>
                </aside>
            </div>
        </div>

        @if ($related->isNotEmpty())
            <section class="bg-section-sunken print-hide section" aria-labelledby="related-title">
                <div class="container-page">
                    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
                        <h2 id="related-title" class="heading-2 min-w-0 break-words">{{ __('articles.related_title') }}</h2>
                        <a href="{{ route('articles.index', $locale) }}" wire:navigate class="link-underline focus-ring inline-flex min-h-11 items-center gap-1.5 text-sm font-semibold text-brand-primary">
                            {{ __('detail_ui.back_articles') }}
                            <x-icon name="arrow-right" class="size-4 rtl:-scale-x-100" aria-hidden="true" />
                        </a>
                    </div>
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($related as $item)
                            <div data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}"><x-article-card :article="$item" as="h3" /></div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </article>
</x-layouts.public>
