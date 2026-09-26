{{--
Tactile file card (folder tab, stacked paper). Props: resource (Resource model or array with title, description, slug, resource_type (enum/string), is_free, category, image),
variant default|compact, url, as h2|h3, skeleton bool.
--}}
@props(['resource' => null, 'variant' => 'default', 'url' => null, 'as' => 'h3', 'skeleton' => false])

@if ($skeleton)
    <div {{ $attributes->merge(['class' => 'relative pt-7']) }} aria-hidden="true">
        <div class="skeleton absolute start-4 top-0 h-8 w-28 !rounded-b-none !rounded-t-xl"></div>
        <div class="card-surface flex gap-4 p-5">
            <div class="skeleton size-20 shrink-0"></div>
            <div class="flex flex-1 flex-col gap-3">
                <div class="skeleton h-5 w-4/5"></div>
                <div class="skeleton h-3 w-full"></div>
                <div class="skeleton h-3 w-2/3"></div>
            </div>
        </div>
    </div>
@else
    @php
    $r = $resource;
    $title = data_get($r, 'title', '');
    $desc = data_get($r, 'description');
    $type = data_get($r, 'resource_type');
    $typeKey = $type instanceof \BackedEnum ? $type->value : $type;
    $free = (bool) data_get($r, 'is_free', false);
    $cat = data_get($r, 'category');
    $catName = is_string($cat) ? $cat : data_get($cat, 'name');
    $fallback = asset('images/resource-placeholder.svg');
    $img = data_get($r, 'image') ?: (is_object($r) && method_exists($r, 'getFirstMediaUrl') ? $r->getFirstMediaUrl('thumbnail') : null);
    $link = $url ?? data_get($r, 'url') ?? (is_object($r) && isset($r->slug) ? route('resources.show', ['locale' => app()->getLocale(), 'slug' => $r->slug]) : '#');
    $Tag = $as;
    $typeIcon = ['pdf' => 'document-text', 'template' => 'document-duplicate', 'questionnaire' => 'clipboard-document-list', 'guide' => 'book-open'][$typeKey] ?? 'document';
    @endphp

    <article {{ $attributes->merge(['class' => 'group relative isolate h-full min-w-0 pt-7']) }}>
        <span class="absolute inset-x-2 bottom-0 top-9 -z-10 translate-y-2 rounded-card border bg-surface-sunken transition-transform duration-base ease-enter [@media(hover:hover)]:group-hover:translate-y-3 [@media(hover:hover)]:group-hover:rotate-1" aria-hidden="true"></span>
        <span class="absolute inset-x-1 bottom-0 top-8 -z-10 translate-y-1 rounded-card border bg-surface-raised transition-transform duration-base ease-enter [@media(hover:hover)]:group-hover:translate-y-1.5 [@media(hover:hover)]:group-hover:-rotate-1" aria-hidden="true"></span>
        <span class="absolute start-4 top-0 z-raised inline-flex h-8 max-w-[70%] items-center gap-1.5 rounded-t-xl border border-b-0 bg-surface-raised px-3 text-xs font-semibold text-brand-primary">
            <x-icon :name="$typeIcon" class="size-4 shrink-0" aria-hidden="true" />
            <span class="truncate">{{ $typeKey ? __('enums.resource_type.' . $typeKey) : '' }}</span>
        </span>
        <div class="card-surface card-hover relative flex h-full min-w-0 items-start gap-4 !rounded-ss-none p-5">
            <div class="img-zoom relative size-20 shrink-0 overflow-hidden rounded-xl border bg-surface-sunken {{ $variant === 'compact' ? 'hidden xs:block' : '' }}">
                <img src="{{ $img ?: $fallback }}" alt="{{ $title }}" width="200" height="200" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='{{ $fallback }}'" class="size-full object-cover dark:brightness-90">
            </div>
            <div class="flex min-w-0 flex-1 flex-col gap-2">
                <{{ $Tag }} class="heading-4 line-clamp-3 break-words"><a href="{{ $link }}" wire:navigate class="focus-ring after:absolute after:inset-0">{{ $title }}</a></{{ $Tag }}>
                @if ($desc && $variant !== 'compact')<p class="line-clamp-2 text-sm text-body">{{ $desc }}</p>@endif
                <div class="mt-auto flex flex-wrap items-center gap-2 pt-1">
                    <x-badge :color="$free ? 'success' : 'warning'" size="sm" dot :text="$free ? __('common.free') : __('common.paid')" />
                    @if ($catName)<span class="min-w-0 truncate text-xs text-muted">{{ $catName }}</span>@endif
                    <span class="ms-auto flex size-9 shrink-0 items-center justify-center rounded-full bg-tint text-brand-primary transition-transform duration-base [@media(hover:hover)]:group-hover:translate-y-0.5"><x-icon name="arrow-down-tray" class="size-5" aria-hidden="true" /></span>
                </div>
            </div>
        </div>
    </article>
@endif
