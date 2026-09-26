@props(['size' => 'md', 'tone' => 'gold', 'label' => null, 'showLabel' => false])

@php
$sizes = ['xs' => 'size-3.5', 'sm' => 'size-4', 'md' => 'size-6', 'lg' => 'size-10', 'xl' => 'size-16'];
$tones = ['gold' => '', 'current' => 'bg-none bg-current', 'brand' => 'bg-none bg-brand'];
$text = $label ?? __('kit_forms.loading');
@endphp

<span role="status" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 align-middle']) }}>
    <span aria-hidden="true" class="star-loader shrink-0 rtl:[animation-direction:reverse] {{ $sizes[$size] ?? $sizes['md'] }} {{ $tones[$tone] ?? '' }}"></span>
    <span class="{{ $showLabel ? 'text-sm text-muted' : 'sr-only' }}">{{ $text }}</span>
</span>
