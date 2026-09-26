{{--
Article-style detail page shell. Props: title (h1), toc bool (auto table of contents, default true), progress bool (reading progress bar, default true), prose bool (wrap body in .prose-content, default true; pass false for mixed component content).
Slots: meta (breadcrumbs first, then badges/byline), default (body), aside (extra sticky sidebar content, e.g. enroll card), share (share buttons), related (related items, pass its own heading).
--}}
@props(['title', 'meta' => null, 'share' => null, 'related' => null, 'aside' => null, 'toc' => true, 'progress' => true, 'prose' => true])

<article {{ $attributes->merge(['class' => 'container-page relative min-w-0 py-8 sm:py-12']) }} data-detail>
    @if ($progress)<x-reading-progress target="[data-detail-body]" />@endif

    <header class="mx-auto max-w-4xl lg:mx-0 lg:max-w-none">
        @isset($meta)
            <div class="mb-5 flex flex-wrap items-center gap-x-3 gap-y-2 text-sm text-body [&>nav]:mb-0 [&>nav]:w-full">{{ $meta }}</div>
        @endisset
        <h1 class="heading-1 max-w-4xl break-words">{{ $title }}</h1>
        <span class="gold-thread mt-6 block max-w-xs" aria-hidden="true"></span>
    </header>

    <div class="mt-8 grid min-w-0 gap-8 lg:grid-cols-[minmax(0,1fr)_16rem] lg:gap-12 xl:grid-cols-[minmax(0,1fr)_18rem]">
        <div class="min-w-0">
            @if ($toc)<x-toc target="[data-detail-body]" class="mb-6 lg:hidden" />@endif

            <div data-detail-body class="{{ $prose ? 'prose-content [&_a[class*=bg-brand]]:!text-[color:var(--color-on-brand)] [&_a[class*=bg-brand]]:no-underline' : 'space-y-6' }} min-w-0 max-w-none">
                {{ $slot }}
            </div>

            @isset($share)
                <div class="mt-10 border-t border-dashed border-strong pt-6 lg:hidden">{{ $share }}</div>
            @endisset
        </div>

        <aside class="min-w-0 space-y-6 lg:sticky lg:top-28 lg:self-start" aria-label="{{ __('kit_sections.article_tools') }}">
            @if ($toc)<x-toc target="[data-detail-body]" class="max-lg:hidden" />@endif
            @isset($aside){{ $aside }}@endisset
            @isset($share)<div class="max-lg:hidden">{{ $share }}</div>@endisset
        </aside>
    </div>

    @isset($related)
        <div class="mt-14 border-t border-dashed border-strong pt-10">{{ $related }}</div>
    @endisset
</article>
