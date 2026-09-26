{{-- Props: state free|paid|disabled, action (POST url, renders a csrf form) or href (plain link), label, meta (e.g. "PDF, 2.4 MB"), tooltip (override for paid/disabled). Paid and disabled stay focusable with an accessible tooltip and never submit. --}}
@props(['state' => 'free', 'action' => null, 'href' => null, 'label' => null, 'meta' => null, 'tooltip' => null])

@php
$locked = $state !== 'free';
$label = $label ?? ($state === 'paid' ? __('kit_sections.download_paid') : __('kit_sections.download'));
$tip = $tooltip ?? ($state === 'paid' ? __('kit_sections.download_paid_tip') : __('kit_sections.download_unavailable_tip'));
$btn = 'group relative inline-flex min-h-12 w-full max-w-full min-w-0 items-center justify-center gap-3 rounded-xl px-6 py-3 text-base font-semibold transition duration-fast ease-enter sm:w-auto ';
$id = 'dl-tip-' . \Illuminate\Support\Str::random(6);
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex min-w-0 max-w-full flex-col items-stretch gap-2 sm:items-start']) }}>
    @if ($locked)
        <div class="relative w-full sm:w-auto" x-data="{ tip: false }" x-on:mouseenter="tip = true" x-on:mouseleave="tip = false" x-on:keydown.escape="tip = false">
            <button type="button" aria-disabled="true" aria-describedby="{{ $id }}"
                class="{{ $btn }} cursor-not-allowed border border-dashed border-strong bg-surface-sunken text-muted focus-ring"
                x-on:click.prevent="tip = !tip" x-on:focus="tip = true" x-on:blur="tip = false">
                <x-icon :name="$state === 'paid' ? 'lock-closed' : 'no-symbol'" class="size-5 shrink-0" aria-hidden="true" />
                <span class="min-w-0 truncate">{{ $label }}</span>
            </button>
            <span id="{{ $id }}" role="tooltip" x-show="tip" x-cloak x-transition.opacity.duration.150ms
                class="pointer-events-none absolute start-1/2 bottom-full z-dropdown mb-2 w-max max-w-[16rem] -translate-x-1/2 rounded-lg bg-text-strong px-3 py-2 text-center text-xs font-medium text-inverse shadow-float rtl:translate-x-1/2">{{ $tip }}</span>
        </div>
    @elseif ($action)
        <form method="POST" action="{{ $action }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="{{ $btn }} shimmer-sweep bg-brand-primary text-on-brand shadow-soft focus-ring active:scale-[0.98] [@media(hover:hover)]:hover:bg-brand-hover [@media(hover:hover)]:hover:shadow-lift">
                <x-icon name="arrow-down-tray" class="size-5 shrink-0 transition-transform duration-base [@media(hover:hover)]:group-hover:translate-y-0.5" aria-hidden="true" />
                <span class="min-w-0 truncate">{{ $label }}</span>
            </button>
        </form>
    @else
        <a href="{{ $href }}" download class="{{ $btn }} shimmer-sweep bg-brand-primary text-on-brand shadow-soft focus-ring active:scale-[0.98] [@media(hover:hover)]:hover:bg-brand-hover [@media(hover:hover)]:hover:shadow-lift">
            <x-icon name="arrow-down-tray" class="size-5 shrink-0" aria-hidden="true" />
            <span class="min-w-0 truncate">{{ $label }}</span>
        </a>
    @endif
    @if ($meta)<p class="text-xs text-muted">{{ $meta }}</p>@endif
</div>
