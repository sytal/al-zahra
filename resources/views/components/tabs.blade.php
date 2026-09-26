@props(['items' => [], 'initial' => null, 'variant' => 'underline', 'label' => null, 'icons' => []])

@php
$first = $initial ?? array_key_first($items);
$isPills = $variant === 'pills';
$list = $isPills
    ? 'w-fit gap-1 rounded-2xl border border-subtle bg-surface-sunken p-1'
    : 'gap-x-1 border-b border-subtle';
$tab = $isPills
    ? 'rounded-xl px-4 aria-selected:bg-surface-raised aria-selected:text-strong aria-selected:shadow-soft'
    : 'rounded-t-xl px-4 after:absolute after:inset-x-3 after:bottom-0 after:h-0.5 after:origin-center after:scale-x-0 after:rounded-full after:bg-brand-secondary after:transition-transform after:duration-base after:ease-enter aria-selected:text-strong aria-selected:after:scale-x-100';
@endphp

<div x-data="tabs({ initial: @js($first) })" {{ $attributes->merge(['class' => 'min-w-0']) }}>
    <div
        role="tablist"
        @if ($label) aria-label="{{ $label }}" @endif
        class="no-scrollbar flex max-w-full overflow-x-auto overscroll-x-contain {{ $list }}"
    >
        @foreach ($items as $id => $title)
            <button
                type="button"
                role="tab"
                data-tab="{{ $id }}"
                x-bind="tab(@js((string) $id))"
                class="relative inline-flex min-h-11 shrink-0 items-center justify-center gap-2 whitespace-nowrap text-sm font-semibold leading-snug text-muted outline-none transition-[color,background-color,box-shadow] duration-fast ease-enter focus-visible:ring-4 focus-visible:ring-brand/30 [@media(hover:hover)]:hover:text-strong {{ $tab }}"
            >
                @if (! empty($icons[$id]))<x-icon :name="$icons[$id]" size="size-4" aria-hidden="true" />@endif
                <span>{{ $title }}</span>
            </button>
        @endforeach
    </div>

    <div class="pt-6">
        {{ $slot }}
    </div>
</div>
