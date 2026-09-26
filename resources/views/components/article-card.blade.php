{{--
Editorial article card. Props: article (Article model or array with title, excerpt, slug, category (model/array/string), reading_time_minutes, published_at, image, author),
variant default|featured|compact, url (override; models get route articles.show), as h2|h3, skeleton bool.
--}}
@props(['article' => null, 'variant' => 'default', 'url' => null, 'as' => 'h3', 'skeleton' => false])

@if ($skeleton)
    <div {{ $attributes->merge(['class' => 'card-surface flex h-full flex-col overflow-hidden']) }} aria-hidden="true">
        <div class="skeleton aspect-[16/10] w-full !rounded-none"></div>
        <div class="flex flex-col gap-3 p-5">
            <div class="skeleton h-3 w-1/3"></div>
            <div class="skeleton h-5 w-full"></div>
            <div class="skeleton h-5 w-4/5"></div>
            <div class="skeleton h-3 w-full"></div>
            <div class="skeleton h-3 w-2/3"></div>
        </div>
    </div>
@else
    @php
    $a = $article;
    $title = data_get($a, 'title', '');
    $excerpt = data_get($a, 'excerpt');
    $cat = data_get($a, 'category');
    $catName = is_string($cat) ? $cat : data_get($cat, 'name');
    $minutes = data_get($a, 'reading_time_minutes');
    $date = data_get($a, 'published_at');
    $dateText = $date instanceof \Carbon\CarbonInterface ? $date->translatedFormat('j M Y') : $date;
    $author = data_get($a, 'author.name', is_string(data_get($a, 'author')) ? data_get($a, 'author') : null);
    $fallback = asset('images/article-placeholder.svg');
    $img = data_get($a, 'image') ?: (is_object($a) && method_exists($a, 'getFirstMediaUrl') ? $a->getFirstMediaUrl('featured_image', 'card') : null);
    $link = $url ?? data_get($a, 'url') ?? (is_object($a) && isset($a->slug) ? route('articles.show', ['locale' => app()->getLocale(), 'slug' => $a->slug]) : '#');
    $Tag = $as;
    @endphp

    @if ($variant === 'compact')
        <article {{ $attributes->merge(['class' => 'group relative flex min-w-0 items-center gap-4 rounded-card p-2 transition-colors duration-fast [@media(hover:hover)]:hover:bg-surface-sunken']) }}>
            <div class="img-zoom relative size-20 shrink-0 overflow-hidden rounded-xl bg-surface-sunken sm:size-24">
                <img src="{{ $img ?: $fallback }}" alt="{{ $title }}" width="200" height="200" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='{{ $fallback }}'" class="size-full object-cover dark:brightness-90">
            </div>
            <div class="min-w-0">
                @if ($catName)<p class="mb-1 truncate text-xs font-semibold text-secondary-text">{{ $catName }}</p>@endif
                <{{ $Tag }} class="line-clamp-3 break-words font-display text-base font-semibold leading-snug text-strong"><a href="{{ $link }}" wire:navigate class="focus-ring after:absolute after:inset-0">{{ $title }}</a></{{ $Tag }}>
                @if ($minutes)<p class="mt-1 text-xs text-muted">{{ $minutes }} {{ __('kit_sections.min_read') }}</p>@endif
            </div>
        </article>
    @elseif ($variant === 'featured')
        <article {{ $attributes->merge(['class' => 'card-surface card-hover group relative grid h-full min-w-0 overflow-hidden md:grid-cols-2']) }}>
            <div class="img-zoom relative aspect-[16/10] w-full overflow-hidden bg-surface-sunken md:aspect-auto md:min-h-full">
                <img src="{{ $img ?: $fallback }}" alt="{{ $title }}" width="960" height="600" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='{{ $fallback }}'" class="absolute inset-0 size-full object-cover dark:brightness-90">
                <x-badge color="warning" variant="solid" size="sm" icon="star" class="absolute start-3 top-3" :text="__('kit_sections.featured')" />
            </div>
            <div class="flex min-w-0 flex-col justify-center gap-4 p-6 sm:p-8 lg:p-10">
                <p class="flex flex-wrap items-center gap-x-2 text-sm text-muted">
                    @if ($catName)<span class="inline-flex min-w-0 max-w-full items-center gap-1.5 break-anywhere font-semibold text-secondary-text"><span class="star-mark text-xs" aria-hidden="true"></span>{{ $catName }}</span>@endif
                    @if ($dateText)<span>{{ $dateText }}</span>@endif
                </p>
                <{{ $Tag }} class="heading-2 line-clamp-4 break-words"><a href="{{ $link }}" wire:navigate class="focus-ring after:absolute after:inset-0">{{ $title }}</a></{{ $Tag }}>
                @if ($excerpt)<p class="lead line-clamp-4">{{ $excerpt }}</p>@endif
                <p class="mt-2 flex items-center gap-3 text-sm text-muted">
                    @if ($author)<span class="truncate font-semibold text-body">{{ $author }}</span>@endif
                    @if ($minutes)<span class="shrink-0">{{ $minutes }} {{ __('kit_sections.min_read') }}</span>@endif
                </p>
            </div>
        </article>
    @else
        <article {{ $attributes->merge(['class' => 'card-surface card-hover group relative flex h-full min-w-0 flex-col overflow-hidden']) }}>
            <div class="img-zoom relative aspect-[16/10] w-full overflow-hidden bg-surface-sunken">
                <img src="{{ $img ?: $fallback }}" alt="{{ $title }}" width="600" height="375" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='{{ $fallback }}'" class="absolute inset-0 size-full object-cover dark:brightness-90">
            </div>
            <div class="flex min-w-0 flex-1 flex-col gap-3 p-5">
                <p class="flex flex-wrap items-center gap-x-2 text-xs text-muted">
                    @if ($catName)<span class="inline-flex min-w-0 max-w-full items-center gap-1.5 break-anywhere font-semibold text-secondary-text"><span class="star-mark text-[0.6rem]" aria-hidden="true"></span>{{ $catName }}</span>@endif
                    @if ($dateText)<span>{{ $dateText }}</span>@endif
                </p>
                <{{ $Tag }} class="heading-4 line-clamp-3 break-words"><a href="{{ $link }}" wire:navigate class="focus-ring after:absolute after:inset-0">{{ $title }}</a></{{ $Tag }}>
                @if ($excerpt)<p class="line-clamp-3 text-sm text-body">{{ $excerpt }}</p>@endif
                <div class="mt-auto flex items-center justify-between gap-3 border-t border-dashed border-strong pt-3 text-xs text-muted">
                    <span class="min-w-0 truncate">@if ($author){{ $author }}@endif</span>
                    @if ($minutes)<span class="inline-flex shrink-0 items-center gap-1"><x-icon name="clock" class="size-4" aria-hidden="true" />{{ $minutes }} {{ __('kit_sections.min_read') }}</span>@endif
                </div>
            </div>
        </article>
    @endif
@endif
