@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconEnd' => null,
    'href' => null,
    'loading' => false,
    'type' => 'button',
    'magnetic' => false,
    'burst' => false,
    'block' => false,
    'label' => null,
    'navigate' => null,
])

@php
$variants = [
    'primary' => 'shimmer-sweep bg-brand text-on-brand shadow-soft ring-1 ring-inset ring-white/10 [@media(hover:hover)]:hover:bg-brand-hover [@media(hover:hover)]:hover:shadow-glow active:bg-brand-active focus-visible:ring-brand/40',
    'secondary' => 'shimmer-sweep bg-brand-secondary text-on-secondary shadow-soft [@media(hover:hover)]:hover:bg-brand-secondary-hover [@media(hover:hover)]:hover:shadow-gold active:bg-brand-secondary-active focus-visible:ring-brand-secondary/50',
    'outline' => 'border-2 border-brand/60 bg-transparent text-link [@media(hover:hover)]:hover:border-brand [@media(hover:hover)]:hover:bg-brand/10 active:bg-brand/15 focus-visible:ring-brand/35',
    'soft' => 'bg-brand/10 text-link [@media(hover:hover)]:hover:bg-brand/15 active:bg-brand/20 focus-visible:ring-brand/35',
    'danger' => 'bg-danger text-on-brand shadow-soft ring-1 ring-inset ring-white/10 [@media(hover:hover)]:hover:bg-danger/90 active:bg-danger/80 focus-visible:ring-danger/40',
    'ghost' => 'bg-transparent text-strong [@media(hover:hover)]:hover:bg-surface-sunken active:bg-subtle/25 focus-visible:ring-brand/35',
    'link' => 'link-underline rounded-md bg-transparent px-1 text-link [@media(hover:hover)]:hover:text-link-hover focus-visible:ring-brand/35',
];
$sizes = [
    'sm' => 'min-h-9 gap-1.5 px-4 py-1.5 text-sm [@media(pointer:coarse)]:min-h-11',
    'md' => 'min-h-11 gap-2 px-5 py-2 text-sm',
    'lg' => 'min-h-12 gap-2.5 px-7 py-2.5 text-base',
    'icon' => 'size-11 p-0 text-sm',
    'icon-sm' => 'size-9 p-0 text-sm [@media(pointer:coarse)]:size-11',
];
$iconSizes = ['sm' => 'size-4', 'md' => 'size-[1.125rem]', 'lg' => 'size-5', 'icon' => 'size-5', 'icon-sm' => 'size-4'];
$variant = array_key_exists($variant, $variants) ? $variant : 'primary';
$size = array_key_exists($size, $sizes) ? $size : 'md';
$isLink = filled($href);
$tag = $isLink ? 'a' : 'button';
$isIconOnly = str_starts_with($size, 'icon');

$classes = 'relative isolate inline-flex select-none items-center justify-center rounded-xl text-center font-semibold leading-snug outline-none transition-[background-color,box-shadow,border-color,transform,color] duration-fast ease-enter '
    . 'focus-visible:ring-4 focus-visible:ring-offset-2 focus-visible:ring-offset-page '
    . 'active:scale-[0.98] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none aria-disabled:pointer-events-none aria-disabled:opacity-50 aria-busy:!opacity-100 '
    . ($variant === 'link' ? 'min-h-11 py-1 ' : $sizes[$size] . ' ')
    . $variants[$variant] . ' '
    . ($block ? 'w-full ' : '');

$mirror = static fn (?string $n): string => $n && preg_match('/(right|left|forward|backward)/', $n) ? 'rtl:-scale-x-100' : '';

$autoNavigate = true;
if ($isLink) {
    $h = (string) $href;
    if (preg_match('#^(https?:)?//#i', $h)) {
        $host = parse_url(str_starts_with($h, '//') ? 'https:' . $h : $h, PHP_URL_HOST);
        $autoNavigate = $host === request()->getHost();
    } elseif (preg_match('/^(mailto|tel|sms|javascript):/i', $h) || str_starts_with($h, '#')) {
        $autoNavigate = false;
    }
    if ($attributes->has('download') || $attributes->get('target') === '_blank') {
        $autoNavigate = false;
    }
}
$useNavigate = $navigate ?? $autoNavigate;

$wireTarget = $attributes->get('wire:target') ?? $attributes->get('wire:click');
$isLoading = (bool) $loading;
@endphp

@if ($burst)
    <span class="star-burst relative {{ $block ? 'flex w-full' : 'inline-flex' }}" x-data="starBurst" x-bind:class="{ 'is-bursting': bursting }"
        @if ($burst === true) x-on:click="fire" @else x-on:{{ $burst }}.window="fire" @endif>
@endif
@if ($magnetic)
    <span class="magnetic {{ $block ? 'flex w-full' : 'inline-flex' }}" x-data="magnetic">
@endif

<{{ $tag }}
    @if ($isLink)
        href="{{ $href }}"
        @if ($useNavigate) wire:navigate @endif
        @if ($attributes->get('target') === '_blank') rel="noopener noreferrer" @endif
    @else
        type="{{ $type }}"
        wire:loading.attr="disabled"
        @if ($wireTarget) wire:target="{{ $wireTarget }}" @endif
        @if ($isLoading) disabled aria-busy="true" @endif
    @endif
    @if ($label) aria-label="{{ $label }}" @endif
    {{ $attributes->except(['wire:target'])->merge(['class' => $classes]) }}
>
    <span class="relative z-[1] inline-flex min-w-0 items-center justify-center {{ $isIconOnly ? '' : 'gap-2' }} transition-opacity duration-fast {{ $isLoading ? 'opacity-0' : '' }}"
        @unless ($isLink) wire:loading.delay.class="opacity-0" @if ($wireTarget) wire:target="{{ $wireTarget }}" @endif @endunless>
        @if ($icon)<x-icon :name="$icon" :size="$iconSizes[$size]" class="shrink-0 {{ $mirror($icon) }}" aria-hidden="true" />@endif
        @if (! $isIconOnly || trim((string) $slot) !== '')<span class="min-w-0 {{ $isIconOnly ? 'sr-only' : '' }}">{{ $slot }}</span>@endif
        @if ($iconEnd)<x-icon :name="$iconEnd" :size="$iconSizes[$size]" class="shrink-0 {{ $mirror($iconEnd) }}" aria-hidden="true" />@endif
    </span>
    @unless ($isLink)
        <span class="pointer-events-none absolute inset-0 z-[2] {{ $isLoading ? 'flex' : 'hidden' }} items-center justify-center"
            @unless ($isLoading) wire:loading.flex.delay @if ($wireTarget) wire:target="{{ $wireTarget }}" @endif @endunless>
            <x-spinner size="sm" tone="current" />
        </span>
    @endunless
</{{ $tag }}>

@if ($magnetic)
    </span>
@endif
@if ($burst)
    </span>
@endif
