{{-- Props: padding (tailwind class), hoverable, variant default|flat|glass|gold, optional named slot "media" (rendered flush above the padded body). --}}
@props(['padding' => 'p-5', 'hoverable' => false, 'variant' => 'default'])

@php
$base = match ($variant) {
    'flat' => 'rounded-card border bg-surface-sunken',
    'glass' => 'glass',
    'gold' => 'gradient-border-gold shadow-soft',
    default => 'card-surface',
};
@endphp

<div {{ $attributes->merge(['class' => 'relative min-w-0 ' . $base . ' ' . ($hoverable ? 'card-hover ' : '') . (isset($media) ? 'overflow-hidden' : $padding)]) }}>
    @isset($media)
        <div class="img-zoom">{{ $media }}</div>
        <div class="{{ $padding }}">{{ $slot }}</div>
    @else
        {{ $slot }}
    @endisset
</div>
