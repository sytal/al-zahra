{{-- Single: to, label, prefix, suffix, decimals, icon. Row: items = [['to'=>int,'label'=>str,'suffix'=>?str,'icon'=>?str], ...]. Numbers count up when scrolled into view (Alpine counter). --}}
@props(['to' => 0, 'label' => null, 'prefix' => '', 'suffix' => '', 'decimals' => 0, 'icon' => null, 'items' => null])

@php
$lc = app()->getLocale() === 'ur-roman' ? 'en' : app()->getLocale();
$rows = $items ?? [['to' => $to, 'label' => $label, 'prefix' => $prefix, 'suffix' => $suffix, 'decimals' => $decimals, 'icon' => $icon]];
@endphp

<dl {{ $attributes->merge(['class' => 'grid gap-x-6 gap-y-8 text-center ' . (count($rows) > 1 ? 'grid-cols-2 ' . ([2 => 'lg:grid-cols-2', 3 => 'lg:grid-cols-3'][count($rows)] ?? 'lg:grid-cols-4') : 'grid-cols-1')]) }}>
    @foreach ($rows as $row)
        @php
            $dec = (int) ($row['decimals'] ?? 0);
            $fallback = ($row['prefix'] ?? '') . number_format((float) $row['to'], $dec) . ($row['suffix'] ?? '');
        @endphp
        <div class="relative flex min-w-0 flex-col px-2 {{ !$loop->last ? 'lg:after:absolute lg:after:end-0 lg:after:top-1/4 lg:after:h-1/2 lg:after:w-px lg:after:bg-[var(--border-strong)]' : '' }}">
            @if (!empty($row['icon']))<x-icon :name="$row['icon']" class="mx-auto mb-2 size-6 text-secondary-text" aria-hidden="true" />@elseif (collect($rows)->contains(fn ($r) => !empty($r['icon'])))<span class="mb-2 block h-6" aria-hidden="true"></span>@endif
            <dd dir="ltr" class="order-1 font-display text-4xl font-bold leading-none tabular-nums text-gradient-brand sm:text-5xl lg:text-6xl"
                x-data="counter({ to: {{ (float) $row['to'] }}, decimals: {{ $dec }}, prefix: @js($row['prefix'] ?? ''), suffix: @js($row['suffix'] ?? ''), locale: '{{ $lc }}' })" x-text="display">{{ $fallback }}</dd>
            <dt class="order-2 mt-2 text-balance text-sm font-medium text-body sm:text-base">{{ $row['label'] }}</dt>
        </div>
    @endforeach
</dl>
