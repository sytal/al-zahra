{{-- Props: icon, label, value (numeric values count up), hint (small text under label). --}}
@props(['icon', 'label', 'value', 'hint' => null])

@php
$numeric = is_numeric($value) && !str_contains((string) $value, '.');
$lc = app()->getLocale() === 'ur-roman' ? 'en' : app()->getLocale();
@endphp

<x-card class="card-hover flex min-w-0 items-center gap-4" padding="p-4 sm:p-5">
    <span class="relative flex size-12 shrink-0 items-center justify-center rounded-2xl bg-tint text-brand-primary sm:size-14">
        <x-icon :name="$icon" class="size-6 sm:size-7" aria-hidden="true" />
        <span class="star-mark absolute -end-1.5 -top-1.5 text-sm" aria-hidden="true"></span>
    </span>
    <div class="min-w-0">
        @if ($numeric)
            <p dir="ltr" class="font-display text-3xl font-bold leading-none tabular-nums text-strong rtl:text-end sm:text-4xl" x-data="counter({ to: {{ (int) $value }}, locale: '{{ $lc }}' })" x-text="display">{{ number_format((int) $value) }}</p>
        @else
            <p class="font-display text-3xl font-bold leading-none text-strong sm:text-4xl break-words">{{ $value }}</p>
        @endif
        <p class="mt-1.5 text-sm font-medium text-body">{{ $label }}</p>
        @if ($hint)<p class="text-xs text-muted">{{ $hint }}</p>@endif
    </div>
</x-card>
