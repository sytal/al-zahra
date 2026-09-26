{{-- Props: percent 0-100, label, size sm|md|lg, tone brand|gold|success, showValue bool. --}}
@props(['percent' => 0, 'label' => null, 'size' => 'md', 'tone' => 'brand', 'showValue' => true])

@php
$p = max(0, min(100, (int) round((float) $percent)));
$h = ['sm' => 'h-1.5', 'md' => 'h-2.5', 'lg' => 'h-4'][$size] ?? 'h-2.5';
$fill = ['brand' => 'bg-brand-gradient', 'gold' => 'bg-gold-gradient', 'success' => 'bg-success'][$tone] ?? 'bg-brand-gradient';
@endphp

<div {{ $attributes }}>
    @if ($label || $showValue)
        <div class="mb-1.5 flex items-baseline justify-between gap-3 text-xs font-medium text-muted">
            <span class="min-w-0 truncate">{{ $label }}</span>
            @if ($showValue)<span class="shrink-0 tabular-nums text-strong">{{ $p }}%</span>@endif
        </div>
    @endif
    <div class="{{ $h }} w-full overflow-hidden rounded-full bg-surface-sunken ring-1 ring-inset ring-[var(--border-color)]" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $p }}" aria-label="{{ $label ?: __('kit_sections.progress') }}">
        <div class="{{ $h }} {{ $fill }} rounded-full transition-[width] duration-slow ease-enter motion-reduce:transition-none" style="width: {{ $p }}%"
            x-data x-init="const t = $el.style.width; $el.style.width = '0%'; requestAnimationFrame(() => requestAnimationFrame(() => $el.style.width = t))"></div>
    </div>
</div>
