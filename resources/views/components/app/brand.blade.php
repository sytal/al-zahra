@props(['collapsed' => false, 'size' => 'md'])

@php
$mark = ['sm' => 'size-8', 'md' => 'size-9', 'lg' => 'size-11'][$size] ?? 'size-9';
$text = ['sm' => 'text-lg', 'md' => 'text-xl', 'lg' => 'text-2xl'][$size] ?? 'text-xl';
@endphp

<span {{ $attributes->class(['inline-flex min-w-0 items-center gap-2.5']) }}>
    <x-brand.logo variant="mark" class="{{ $mark }} shrink-0" />
    @unless ($collapsed)
        <span class="truncate font-display {{ $text }} font-bold leading-none text-strong">{{ config('app.name') }}</span>
    @endunless
</span>
