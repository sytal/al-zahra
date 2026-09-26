@props(['headers' => [], 'stacked' => true, 'sticky' => false, 'zebra' => true, 'hover' => true, 'caption' => null, 'dense' => false])

@php
$cards = $stacked && count($headers) > 0;
$body = (string) $slot;
$isEmpty = trim(strip_tags($body)) === '' && ! str_contains($body, '<td') && ! str_contains($body, '<th');

if ($cards && ! $isEmpty) {
    $body = preg_replace_callback('/<tr\b[^>]*>.*?<\/tr>/is', function ($row) use ($headers) {
        $i = -1;

        return preg_replace_callback('/<td\b(?![^>]*\bdata-label=)/i', function () use (&$i, $headers) {
            $i++;

            return '<td data-label="' . e($headers[$i] ?? '') . '"';
        }, $row[0]);
    }, $body);
}

$cellY = $dense
    ? ($cards ? 'md:[&_tbody_td]:py-2.5' : '[&_tbody_td]:py-2.5')
    : ($cards ? 'md:[&_tbody_td]:py-3.5' : '[&_tbody_td]:py-3.5');
$cellX = $cards ? 'md:[&_tbody_td]:px-4 max-md:[&_tbody_td:first-child]:font-semibold max-md:[&_tbody_td:first-child]:text-strong' : '[&_tbody_td]:px-4';
$zebraClass = $zebra ? ($cards ? 'md:[&_tbody_tr:nth-child(even)]:bg-surface-sunken/60' : '[&_tbody_tr:nth-child(even)]:bg-surface-sunken/60') : '';
$hoverClass = $hover ? '[@media(hover:hover)]:[&_tbody_tr:hover]:bg-tint' : '';
$frame = $cards
    ? 'md:overflow-x-auto md:overscroll-x-contain md:rounded-2xl md:border md:border-subtle md:bg-surface-raised md:shadow-soft'
    : 'overflow-x-auto overscroll-x-contain rounded-2xl border border-subtle bg-surface-raised shadow-soft';
@endphp

<div {{ $attributes->merge(['class' => $frame . ($sticky ? ' max-h-[70dvh] overflow-y-auto' : '')]) }}>
    <table class="{{ $cards ? 'table-cards md:min-w-[36rem]' : 'min-w-[36rem]' }} w-full border-collapse text-start [&_tbody_td]:text-sm [&_tbody_td]:text-body {{ $cellX }} {{ $cellY }} {{ $zebraClass }} {{ $hoverClass }}">
        @if ($caption)<caption class="px-4 py-3 text-start text-sm font-semibold text-strong">{{ $caption }}</caption>@endif
        @if (count($headers))
            <thead class="{{ $sticky ? 'sticky top-0 z-raised' : '' }}">
                <tr class="bg-surface-sunken">
                    @foreach ($headers as $header)
                        <th scope="col" class="border-b border-subtle bg-surface-sunken px-4 py-3 text-start text-xs font-bold tracking-wide text-muted">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody class="{{ $cards ? 'md:divide-y md:divide-subtle' : 'divide-y divide-subtle' }}">
            @if ($isEmpty && isset($empty))
                <tr>
                    <td colspan="{{ max(1, count($headers)) }}" class="justify-center px-4 py-10 text-center" data-label="">{{ $empty }}</td>
                </tr>
            @else
                {!! $body !!}
            @endif
        </tbody>
    </table>
</div>
