{{-- Bento grid wrapper. Children: <x-bento.tile size="default|wide|tall|hero" tone="surface|tint|brand|gold|dark" icon title>. 1 col mobile, 2 col sm, 4 col lg. --}}
<div {{ $attributes->merge(['class' => 'bento']) }}>
    {{ $slot }}
</div>
