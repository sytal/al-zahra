{{-- Props: text (or slot), url (renders a link when set), icon, active. --}}
@props(['text' => null, 'url' => null, 'icon' => null, 'active' => false])

@php
$cls = 'inline-flex min-h-8 max-w-full items-center gap-1.5 rounded-full border px-3 py-1 text-sm transition duration-fast ease-enter '
    . ($active ? 'border-brand-primary bg-brand-primary text-on-brand' : 'border-subtle bg-surface-raised text-body');
@endphp

@if ($url)
    <a href="{{ $url }}" wire:navigate {{ $attributes->merge(['class' => $cls . ' focus-ring [@media(hover:hover)]:hover:border-brand-primary [@media(hover:hover)]:hover:text-brand-primary active:scale-[0.98]']) }}>
        @if ($icon)<x-icon :name="$icon" class="size-4 shrink-0" aria-hidden="true" />@else<span class="star-mark text-[0.6rem]" aria-hidden="true"></span>@endif
        <span class="min-w-0 break-words">{{ $text ?? $slot }}</span>
    </a>
@else
    <span {{ $attributes->merge(['class' => $cls]) }}>
        @if ($icon)<x-icon :name="$icon" class="size-4 shrink-0" aria-hidden="true" />@else<span class="star-mark text-[0.6rem]" aria-hidden="true"></span>@endif
        <span class="min-w-0 break-words">{{ $text ?? $slot }}</span>
    </span>
@endif
