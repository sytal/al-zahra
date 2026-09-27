@props(['paginator', 'livewire' => null, 'sides' => 1])

@php
$isLengthAware = method_exists($paginator, 'lastPage');
$current = (int) $paginator->currentPage();
$last = $isLengthAware ? (int) $paginator->lastPage() : $current + ($paginator->hasMorePages() ? 1 : 0);
$pageName = method_exists($paginator, 'getPageName') ? $paginator->getPageName() : 'page';
$cur = class_exists(\Livewire\Livewire::class) ? \Livewire\Livewire::current() : null;
$useLivewire = $livewire ?? ($cur !== null && method_exists($cur, 'gotoPage'));

$pages = [];
if ($isLengthAware) {
    $from = max(1, $current - $sides);
    $to = min($last, $current + $sides);
    $pages[] = 1;
    if ($from > 2) {
        $pages[] = '...';
    }
    for ($p = max(2, $from); $p <= min($last - 1, $to); $p++) {
        $pages[] = $p;
    }
    if ($to < $last - 1) {
        $pages[] = '...';
    }
    if ($last > 1) {
        $pages[] = $last;
    }
}

$btn = 'relative inline-flex min-h-11 min-w-11 select-none items-center justify-center gap-1.5 rounded-xl px-3 text-sm font-semibold outline-none transition-[background-color,color,box-shadow,transform,border-color] duration-fast ease-enter focus-visible:ring-4 focus-visible:ring-brand/30 active:scale-95';
$idle = $btn . ' border border-subtle bg-surface-raised text-body [@media(hover:hover)]:hover:border-brand/50 [@media(hover:hover)]:hover:bg-tint [@media(hover:hover)]:hover:text-strong';
$active = $btn . ' bg-brand text-on-brand shadow-glow';
$off = $btn . ' cursor-not-allowed border border-subtle bg-surface-sunken text-subtle';
$scroll = <<<'JS'
    (function (el) {
        var target = el.closest('[data-results]') || document.querySelector('[data-results]');
        var smooth = !matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (target) {
            var top = target.getBoundingClientRect().top + window.pageYOffset - 96;
            window.scrollTo({ top: top, behavior: smooth ? 'smooth' : 'auto' });
        } else {
            window.scrollTo({ top: 0, behavior: smooth ? 'smooth' : 'auto' });
        }
    })($el)
JS;
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('kit_forms.pagination_label') }}" class="mt-10 flex flex-col items-center gap-4 md:flex-row md:justify-between">
        @if ($isLengthAware && $paginator->firstItem())
            <p class="hidden text-sm text-muted md:block">{{ __('kit_forms.showing', ['from' => $paginator->firstItem(), 'to' => $paginator->lastItem(), 'total' => $paginator->total()]) }}</p>
        @endif

        <div class="flex w-full items-center justify-between gap-2 md:w-auto md:justify-end">
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" role="link" aria-label="{{ __('kit_forms.previous_page') }}" class="{{ $off }}">
                    <x-icon name="chevron-left" size="size-5" class="rtl:-scale-x-100" aria-hidden="true" />
                </span>
            @elseif ($useLivewire)
                <button type="button" wire:click="previousPage('{{ $pageName }}')" x-on:click="{{ $scroll }}" wire:loading.attr="disabled" aria-label="{{ __('kit_forms.previous_page') }}" class="{{ $idle }}">
                    <x-icon name="chevron-left" size="size-5" class="rtl:-scale-x-100" aria-hidden="true" />
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" wire:navigate rel="prev" aria-label="{{ __('kit_forms.previous_page') }}" class="{{ $idle }}">
                    <x-icon name="chevron-left" size="size-5" class="rtl:-scale-x-100" aria-hidden="true" />
                </a>
            @endif

            @if ($isLengthAware)
                <p class="min-w-0 text-center text-sm font-semibold tabular-nums text-strong md:hidden">{{ __('kit_forms.page_of', ['current' => $current, 'total' => $last]) }}</p>

                <ul class="hidden items-center gap-1.5 md:flex">
                    @foreach ($pages as $p)
                        @if ($p === '...')
                            <li aria-hidden="true" class="grid min-h-11 min-w-8 place-items-center text-muted">&hellip;</li>
                        @elseif ($p === $current)
                            <li>
                                <span aria-current="page" aria-label="{{ __('kit_forms.current_page', ['page' => $p]) }}" class="{{ $active }} tabular-nums">
                                    {{ $p }}
                                    <span aria-hidden="true" class="star-mark absolute inset-x-0 -bottom-1.5 mx-auto text-[0.65rem]"></span>
                                </span>
                            </li>
                        @else
                            <li>
                                @if ($useLivewire)
                                    <button type="button" wire:click="gotoPage({{ $p }}, '{{ $pageName }}')" x-on:click="{{ $scroll }}" aria-label="{{ __('kit_forms.go_to_page', ['page' => $p]) }}" class="{{ $idle }} tabular-nums">{{ $p }}</button>
                                @else
                                    <a href="{{ $paginator->url($p) }}" wire:navigate aria-label="{{ __('kit_forms.go_to_page', ['page' => $p]) }}" class="{{ $idle }} tabular-nums">{{ $p }}</a>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>
            @endif

            @if ($paginator->hasMorePages())
                @if ($useLivewire)
                    <button type="button" wire:click="nextPage('{{ $pageName }}')" x-on:click="{{ $scroll }}" wire:loading.attr="disabled" aria-label="{{ __('kit_forms.next_page') }}" class="{{ $idle }}">
                        <x-icon name="chevron-right" size="size-5" class="rtl:-scale-x-100" aria-hidden="true" />
                    </button>
                @else
                    <a href="{{ $paginator->nextPageUrl() }}" wire:navigate rel="next" aria-label="{{ __('kit_forms.next_page') }}" class="{{ $idle }}">
                        <x-icon name="chevron-right" size="size-5" class="rtl:-scale-x-100" aria-hidden="true" />
                    </a>
                @endif
            @else
                <span aria-disabled="true" role="link" aria-label="{{ __('kit_forms.next_page') }}" class="{{ $off }}">
                    <x-icon name="chevron-right" size="size-5" class="rtl:-scale-x-100" aria-hidden="true" />
                </span>
            @endif
        </div>
    </nav>
@endif
