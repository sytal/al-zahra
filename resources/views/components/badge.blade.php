@props(['color' => 'neutral', 'text' => null])

@php
$colors = [
    'brand' => 'bg-brand-primary/10 text-brand-primary',
    'success' => 'bg-success/10 text-success',
    'warning' => 'bg-brand-secondary/10 text-brand-secondary',
    'danger' => 'bg-danger/10 text-danger',
    'neutral' => 'bg-ink/10 text-ink',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ' . $colors[$color]]) }}>
    {{ $text ?? $slot }}
</span>
