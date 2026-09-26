@props(['variant' => 'horizontal', 'tagline' => null])
@php
    $mk = '<path fill-rule="evenodd" fill="currentColor" d="M32 2L40.78 10.8L53.21 10.79L53.2 23.22L62 32L53.2 40.78L53.21 53.21L40.78 53.2L32 62L23.22 53.2L10.79 53.21L10.8 40.78L2 32L10.8 23.22L10.79 10.79L23.22 10.8ZM19 32a13 13 0 1 0 26 0a13 13 0 1 0 -26 0Z"/><g class="fill-brand-secondary"><rect x="22.5" y="29" width="3" height="6" rx="1.5"/><rect x="26.5" y="26" width="3" height="12" rx="1.5"/><rect x="30.5" y="23" width="3" height="18" rx="1.5"/><rect x="34.5" y="26" width="3" height="12" rx="1.5"/><rect x="38.5" y="29" width="3" height="6" rx="1.5"/></g>';
@endphp
@if ($variant === 'mark')
<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" style="direction:ltr;overflow:visible" aria-hidden="true" {{ $attributes->class(['text-brand-primary']) }}>{!! $mk !!}</svg>
@elseif ($variant === 'stacked')
<svg viewBox="0 0 200 {{ $tagline ? 150 : 130 }}" fill="none" xmlns="http://www.w3.org/2000/svg" style="direction:ltr;overflow:visible" role="img" aria-label="Al Zahra" {{ $attributes->class(['text-brand-primary']) }}>
    <g transform="translate(68 0)">{!! $mk !!}</g>
    <text x="100" y="118" text-anchor="middle" font-family="Georgia,'Times New Roman',serif" font-size="34" font-weight="700" fill="currentColor" class="text-ink">Al Zahra</text>
    @if ($tagline)<text x="102" y="145" text-anchor="middle" font-family="Helvetica,Arial,sans-serif" font-size="12" font-weight="600" letter-spacing="5" fill="currentColor">{{ $tagline }}</text>@endif
</svg>
@else
<svg viewBox="0 0 {{ $tagline ? 290 : 220 }} 64" fill="none" xmlns="http://www.w3.org/2000/svg" style="direction:ltr;overflow:visible" role="img" aria-label="Al Zahra" {{ $attributes->class(['text-brand-primary']) }}>
    <g>{!! $mk !!}</g>
    <text x="76" y="{{ $tagline ? 38 : 43 }}" font-family="Georgia,'Times New Roman',serif" font-size="32" font-weight="700" fill="currentColor" class="text-ink">Al Zahra</text>
    @if ($tagline)<text x="78" y="55" font-family="Helvetica,Arial,sans-serif" font-size="11" font-weight="600" letter-spacing="4.5" fill="currentColor">{{ $tagline }}</text>@endif
</svg>
@endif
