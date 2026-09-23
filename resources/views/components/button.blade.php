@props(['variant' => 'primary', 'size' => 'md', 'icon' => null, 'href' => null, 'loading' => false, 'type' => 'button'])

@php
$variants = [
    'primary' => 'bg-brand-primary text-white hover:bg-brand-primary/90 focus-visible:outline-brand-primary',
    'secondary' => 'bg-brand-secondary text-ink hover:bg-brand-secondary/90 focus-visible:outline-brand-secondary',
    'outline' => 'border border-brand-primary text-brand-primary hover:bg-brand-primary/10',
    'danger' => 'bg-danger text-white hover:bg-danger/90',
    'ghost' => 'text-ink hover:bg-surface',
];
$sizes = [
    'sm' => 'px-3 py-1.5 text-sm gap-1.5',
    'md' => 'px-4 py-2 text-sm gap-2',
    'lg' => 'px-6 py-3 text-base gap-2',
];
$classes = 'inline-flex items-center justify-center rounded-lg font-medium transition duration-200 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 '
    . $variants[$variant] . ' ' . $sizes[$size];
@endphp

@if ($href)
    <a href="{{ $href }}" wire:navigate {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)<x-icon :name="$icon" class="size-4" />@endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} wire:loading.attr="disabled">
        <span wire:loading.remove>
            @if ($icon)<x-icon :name="$icon" class="size-4" />@endif
            {{ $slot }}
        </span>
        <span wire:loading class="inline-flex items-center gap-2">
            <x-icon name="arrow-path" class="size-4 animate-spin" />
            {{ $slot }}
        </span>
    </button>
@endif
