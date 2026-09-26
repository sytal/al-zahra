{{-- Props: color brand|success|warning|danger|info|accent|neutral, variant soft|solid|outline, size sm|md, dot bool, icon heroicon name, text (or slot). --}}
@props(['color' => 'neutral', 'text' => null, 'variant' => 'soft', 'size' => 'md', 'dot' => false, 'icon' => null])

@php
$styles = [
    'soft' => [
        'brand' => 'bg-brand-primary/10 text-brand-primary',
        'success' => 'bg-status-published-bg text-status-published-text',
        'warning' => 'bg-secondary/20 text-secondary-text',
        'danger' => 'bg-danger/10 text-danger',
        'info' => 'bg-status-info-bg text-status-info-text',
        'accent' => 'bg-accent/10 text-accent',
        'neutral' => 'bg-surface-sunken text-body',
    ],
    'solid' => [
        'brand' => 'bg-brand-primary text-on-brand',
        'success' => 'bg-success text-on-brand',
        'warning' => 'bg-secondary text-on-secondary',
        'danger' => 'bg-danger text-on-brand',
        'info' => 'bg-info text-on-brand',
        'accent' => 'bg-accent text-on-brand',
        'neutral' => 'bg-text-strong text-inverse',
    ],
    'outline' => [
        'brand' => 'border border-brand-primary/50 text-brand-primary',
        'success' => 'border border-success/50 text-status-published-text',
        'warning' => 'border border-secondary/60 text-secondary-text',
        'danger' => 'border border-danger/50 text-danger',
        'info' => 'border border-info/50 text-status-info-text',
        'accent' => 'border border-accent/50 text-accent',
        'neutral' => 'border text-body',
    ],
];
$sizes = ['sm' => 'px-2 py-0.5 text-[0.6875rem] gap-1', 'md' => 'px-2.5 py-1 text-xs gap-1.5'];
$cls = ($styles[$variant] ?? $styles['soft'])[$color] ?? $styles['soft']['neutral'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex max-w-full items-center rounded-full font-semibold leading-tight ' . ($sizes[$size] ?? $sizes['md']) . ' ' . $cls]) }}>
    @if ($dot)<span class="size-1.5 shrink-0 rounded-full bg-current" aria-hidden="true"></span>@endif
    @if ($icon)<x-icon :name="$icon" class="size-3.5 shrink-0" aria-hidden="true" />@endif
    <span class="min-w-0 truncate">{{ $text ?? $slot }}</span>
</span>
