{{--
Document-like research card with a citation strip. Props: paper (ResearchPaper model or array with title, findings_summary, slug, category, published_year, co_authors (array), image),
variant default|compact, url, as h2|h3, skeleton bool.
--}}
@props(['paper' => null, 'variant' => 'default', 'url' => null, 'as' => 'h3', 'skeleton' => false])

@if ($skeleton)
    <div {{ $attributes->merge(['class' => 'card-surface flex h-full flex-col overflow-hidden border-s-4 border-s-[var(--border-strong)]']) }} aria-hidden="true">
        <div class="flex flex-col gap-3 p-5">
            <div class="skeleton h-3 w-1/3"></div>
            <div class="skeleton h-5 w-full"></div>
            <div class="skeleton h-5 w-3/4"></div>
            <div class="skeleton h-3 w-full"></div>
            <div class="skeleton h-3 w-5/6"></div>
        </div>
        <div class="skeleton mt-auto h-10 w-full !rounded-none"></div>
    </div>
@else
    @php
    $p = $paper;
    $title = data_get($p, 'title', '');
    $summary = \Illuminate\Support\Str::limit((string) data_get($p, 'findings_summary', ''), $variant === 'compact' ? 110 : 170);
    $cat = data_get($p, 'category');
    $catName = is_string($cat) ? $cat : data_get($cat, 'name');
    $year = data_get($p, 'published_year');
    $authors = collect(data_get($p, 'co_authors', []) ?: [])->filter()->values();
    $authorText = $authors->count() > 2 ? $authors->take(2)->implode(', ') . ' ' . __('kit_sections.et_al') : $authors->implode(', ');
    $fallback = asset('images/research-placeholder.svg');
    $img = data_get($p, 'image') ?: (is_object($p) && method_exists($p, 'getFirstMediaUrl') ? $p->getFirstMediaUrl('cover_image') : null);
    $link = $url ?? data_get($p, 'url') ?? (is_object($p) && isset($p->slug) ? route('research.show', ['locale' => app()->getLocale(), 'slug' => $p->slug]) : '#');
    $Tag = $as;
    @endphp

    <article {{ $attributes->merge(['class' => 'card-surface card-hover group relative flex h-full min-w-0 flex-col overflow-hidden border-s-4 border-s-[var(--color-brand-secondary)]']) }}>
        <span class="pointer-events-none absolute end-0 top-0 size-8 bg-gradient-to-bl from-[var(--color-surface-sunken)] from-50% to-transparent to-50% [clip-path:polygon(0_0,100%_0,100%_100%)] rtl:-scale-x-100" aria-hidden="true"></span>
        <div class="flex min-w-0 flex-1 gap-4 p-5">
            <div class="flex min-w-0 flex-1 flex-col gap-3">
                <p class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs font-semibold text-secondary-text">
                    <x-icon name="beaker" class="size-4 shrink-0" aria-hidden="true" />
                    @if ($catName)<span class="min-w-0 truncate">{{ $catName }}</span>@endif
                    @if ($year)<x-badge size="sm" variant="outline" color="neutral" :text="(string) $year" />@endif
                </p>
                <{{ $Tag }} class="{{ $variant === 'compact' ? 'heading-4' : 'heading-3' }} line-clamp-4 break-words"><a href="{{ $link }}" wire:navigate class="focus-ring after:absolute after:inset-0">{{ $title }}</a></{{ $Tag }}>
                @if ($summary)
                    <p class="text-sm text-body"><span class="me-1.5 font-semibold text-strong">{{ __('kit_sections.findings') }}:</span>{{ $summary }}</p>
                @endif
            </div>
            @if ($variant !== 'compact')
                <div class="relative hidden h-28 w-20 shrink-0 self-start overflow-hidden rounded-md border bg-surface-sunken shadow-soft transition-transform duration-base ease-enter xs:block [@media(hover:hover)]:group-hover:-rotate-2">
                    <img src="{{ $img ?: $fallback }}" alt="{{ $title }}" width="160" height="224" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='{{ $fallback }}'" class="size-full object-cover dark:brightness-90">
                </div>
            @endif
        </div>
        <div class="mt-auto flex items-center gap-2 border-t border-dashed border-strong bg-surface-sunken px-5 py-2.5 text-xs text-muted">
            <x-icon name="bookmark" class="size-4 shrink-0" aria-hidden="true" />
            <span class="min-w-0 flex-1 truncate">{{ $authorText ?: __('kit_sections.research_paper') }}@if ($year) ({{ $year }})@endif</span>
            <x-icon name="arrow-right" class="size-4 shrink-0 text-brand-primary rtl:-scale-x-100" aria-hidden="true" />
        </div>
    </article>
@endif
