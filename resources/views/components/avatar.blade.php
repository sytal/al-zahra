@props(['src' => null, 'name' => '', 'size' => 'md', 'status' => null, 'shape' => 'circle', 'ringed' => false, 'decorative' => false])

@php
$sizes = [
    'xs' => 'size-6 text-[0.625rem]',
    'sm' => 'size-8 text-xs',
    'md' => 'size-11 text-sm',
    'lg' => 'size-16 text-xl',
    'xl' => 'size-24 text-3xl',
];
$dot = ['xs' => 'size-1.5', 'sm' => 'size-2', 'md' => 'size-3', 'lg' => 'size-3.5', 'xl' => 'size-4'];
$tones = ['bg-brand/15 text-link', 'bg-brand-secondary/25 text-strong', 'bg-accent/15 text-strong'];
$words = preg_split('/\s+/u', trim((string) $name), -1, PREG_SPLIT_NO_EMPTY) ?: [];
$initials = '';
foreach (array_slice($words, 0, 2) as $w) {
    $initials .= mb_strtoupper(mb_substr($w, 0, 1));
}
$tone = $tones[crc32((string) $name) % 3];
$statuses = ['online' => 'bg-success', 'away' => 'bg-warning', 'offline' => 'bg-subtle'];
$radius = $shape === 'rounded' ? 'rounded-2xl' : 'rounded-full';
$sz = $sizes[$size] ?? $sizes['md'];
@endphp

<span
    x-data="{ ok: {{ $src ? 'true' : 'false' }} }"
    {{ $attributes->merge(['class' => "relative inline-grid shrink-0 place-items-center overflow-visible font-display font-semibold select-none {$sz}"]) }}
    @if ($name && ! $decorative) role="img" aria-label="{{ $name }}" @endif
>
    <span class="grid size-full place-items-center overflow-hidden {{ $radius }} {{ $tone }} {{ $ringed ? 'ring-2 ring-surface-raised' : '' }}">
        @if ($src)
            <img src="{{ $src }}" alt="" width="96" height="96" loading="lazy" decoding="async" x-show="ok" x-on:error="ok = false" class="size-full object-cover" />
        @endif
        <span x-show="!ok" @if ($src) x-cloak @endif aria-hidden="true">
            @if ($initials !== '')
                {{ $initials }}
            @else
                <x-icon name="user" size="size-1/2" aria-hidden="true" />
            @endif
        </span>
    </span>
    @if ($status && isset($statuses[$status]))
        <span aria-hidden="true" class="absolute -bottom-px -end-px rounded-full border-2 border-surface-raised {{ $statuses[$status] }} {{ $dot[$size] ?? $dot['md'] }}"></span>
    @endif
</span>
