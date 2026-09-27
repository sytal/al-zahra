@props(['items' => []])

@php
$cols = [1 => 'lg:grid-cols-1', 2 => 'lg:grid-cols-2', 3 => 'lg:grid-cols-3', 4 => 'lg:grid-cols-4'][min(max(count($items), 1), 4)];
@endphp

<dl {{ $attributes->class(['grid grid-cols-1 gap-3 xs:grid-cols-2', $cols]) }}>
    @foreach ($items as $item)
        <div class="card-surface relative flex items-center gap-4 overflow-hidden p-4 sm:p-5">
            @if (!empty($item['icon']))
                <span class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-tint text-brand-primary">
                    <x-icon :name="$item['icon']" class="size-6" />
                </span>
            @endif
            <div class="flex min-w-0 flex-col-reverse">
                <dt class="mt-1 truncate text-sm text-body">{{ $item['label'] }}</dt>
                <dd class="numeral-display text-3xl leading-none text-strong">
                    @if (is_numeric($item['value']))
                        <span x-data="counter({ to: {{ (float) $item['value'] }}, locale: '{{ app()->getLocale() === 'ur-roman' ? 'en' : app()->getLocale() }}' })" x-text="display">{{ $item['value'] }}</span>
                    @else
                        {{ $item['value'] }}
                    @endif
                </dd>
            </div>
            <span class="star-mark absolute -bottom-2 -end-2 text-5xl opacity-10" aria-hidden="true"></span>
        </div>
    @endforeach
</dl>
