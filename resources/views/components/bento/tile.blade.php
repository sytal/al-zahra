{{-- Props: size default|wide|tall|hero, tone surface|tint|brand|gold|dark, icon, title, text, url. Slot renders under the text. --}}
@props(['size' => 'default', 'tone' => 'surface', 'icon' => null, 'title' => null, 'text' => null, 'url' => null])

@php
$span = ['wide' => 'bento-wide', 'tall' => 'bento-tall', 'hero' => 'bento-hero'][$size] ?? '';
$tones = [
    'surface' => 'card-surface',
    'tint' => 'rounded-card border bg-tint',
    'brand' => 'rounded-card bg-brand-gradient text-on-brand shadow-soft',
    'gold' => 'rounded-card bg-gold-gradient text-on-secondary shadow-gold',
    'dark' => 'rounded-card bg-section-dark text-strong shadow-soft',
];
$onFill = in_array($tone, ['brand', 'gold'], true);
@endphp

<div {{ $attributes->merge(['class' => 'group relative isolate flex min-w-0 flex-col gap-3 overflow-hidden p-5 transition duration-base ease-enter sm:p-6 ' . $span . ' ' . ($tones[$tone] ?? $tones['surface']) . ($url ? ' card-hover' : '')]) }}>
    @if ($icon)
        <span class="flex size-11 shrink-0 items-center justify-center rounded-xl {{ $onFill ? 'bg-white/20' : 'bg-tint text-brand-primary' }}">
            <x-icon :name="$icon" class="size-6" aria-hidden="true" />
        </span>
    @endif
    @if ($title)
        <h3 class="heading-4 break-words {{ $onFill ? '!text-current' : '' }}">
            @if ($url)<a href="{{ $url }}" wire:navigate class="focus-ring after:absolute after:inset-0">{{ $title }}</a>@else{{ $title }}@endif
        </h3>
    @endif
    @if ($text)<p class="text-sm {{ $onFill ? 'opacity-90' : 'text-body' }}">{{ $text }}</p>@endif
    {{ $slot }}
    @if ($size === 'hero')<span class="star-mark pointer-events-none absolute -bottom-10 -end-10 -z-10 text-[12rem] opacity-15" aria-hidden="true"></span>@endif
</div>
