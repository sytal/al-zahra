{{-- Props: percent 0-100, size (px), stroke (px), label (accessible name). Default slot replaces the centre text. --}}
@props(['percent' => 0, 'size' => 64, 'stroke' => 6, 'label' => null])

@php
$p = max(0, min(100, (int) round((float) $percent)));
$sw = round($stroke / $size * 48, 2);
$r = round(24 - $sw / 2, 2);
@endphp

<div {{ $attributes->merge(['class' => 'relative inline-flex shrink-0 items-center justify-center']) }} style="width: {{ $size }}px; height: {{ $size }}px" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $p }}" aria-label="{{ $label ?? __('kit_sections.progress') }}">
    <svg viewBox="0 0 48 48" class="size-full -rotate-90" aria-hidden="true">
        <circle cx="24" cy="24" r="{{ $r }}" fill="none" stroke-width="{{ $sw }}" style="stroke: var(--border-strong)" opacity="0.6" />
        <circle cx="24" cy="24" r="{{ $r }}" fill="none" stroke-width="{{ $sw }}" stroke-linecap="round" pathLength="100" class="stroke-brand-primary transition-[stroke-dasharray] duration-slow ease-enter motion-reduce:transition-none" stroke-dasharray="{{ $p }} 100"
            x-data x-init="const t = $el.getAttribute('stroke-dasharray'); $el.setAttribute('stroke-dasharray', '0 100'); requestAnimationFrame(() => requestAnimationFrame(() => $el.setAttribute('stroke-dasharray', t)))" />
    </svg>
    <span class="absolute inset-0 flex items-center justify-center font-bold tabular-nums text-strong" style="font-size: {{ max(10, round($size * 0.22)) }}px">
        @if (trim((string) $slot) !== ''){{ $slot }}@else{{ $p }}%@endif
    </span>
</div>
