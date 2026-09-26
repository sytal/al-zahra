{{-- Props: instructor (User model or array with name, avatar (url), bio), roleLabel (defaults to common.instructor). --}}
@props(['instructor', 'roleLabel' => null])

@php
$avatar = data_get($instructor, 'avatar') ?: (is_object($instructor) && method_exists($instructor, 'getFirstMediaUrl') ? $instructor->getFirstMediaUrl('avatar') : null);
@endphp

<x-card {{ $attributes->merge(['class' => 'flex items-center gap-4']) }} padding="p-4 sm:p-5">
    <span class="relative shrink-0">
        <img src="{{ $avatar ?: asset('images/avatar-placeholder.svg') }}" alt="{{ data_get($instructor, 'name') }}" width="64" height="64" loading="lazy" decoding="async" class="size-14 rounded-full object-cover ring-2 ring-[var(--color-brand-secondary)] ring-offset-2 ring-offset-[var(--color-surface-raised)] sm:size-16">
        <span class="star-mark absolute -end-1 -top-1 text-sm" aria-hidden="true"></span>
    </span>
    <div class="min-w-0">
        <p class="break-words font-display text-lg font-semibold text-strong">{{ data_get($instructor, 'name') }}</p>
        <p class="text-sm text-secondary-text">{{ $roleLabel ?? __('common.instructor') }}</p>
        @if (data_get($instructor, 'bio'))<p class="mt-1 line-clamp-2 text-sm text-body">{{ data_get($instructor, 'bio') }}</p>@endif
    </div>
</x-card>
