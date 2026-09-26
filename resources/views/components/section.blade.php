{{-- Props: variant light|tinted|sunken|pattern|dark, id, tight bool, thread bool (gold hairline on top), narrow bool, container bool. Alternate variants down a page for rhythm. --}}
@props(['variant' => 'light', 'id' => null, 'tight' => false, 'thread' => false, 'narrow' => false, 'container' => true])

@php
$bg = ['light' => 'bg-section-light', 'tinted' => 'bg-section-tinted', 'sunken' => 'bg-section-sunken', 'pattern' => 'bg-section-pattern', 'dark' => 'bg-section-dark'][$variant] ?? 'bg-section-light';
@endphp

<section @if ($id) id="{{ $id }}" @endif {{ $attributes->merge(['class' => 'relative isolate min-w-0 overflow-hidden ' . ($tight ? 'section-tight ' : 'section ') . $bg]) }}>
    @if ($thread)<span class="gold-thread absolute inset-x-0 top-0" aria-hidden="true"></span>@endif
    @if ($variant === 'dark')
        <span class="glow-gold -top-40 end-[-8rem] opacity-40" style="--glow-size: 26rem" aria-hidden="true"></span>
        <span class="light-rays" aria-hidden="true"></span>
    @endif
    @if ($container)
        <div class="container-page {{ $narrow ? 'container-narrow' : '' }}">{{ $slot }}</div>
    @else
        {{ $slot }}
    @endif
</section>
