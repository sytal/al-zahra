@props(['user', 'collapsible' => false])

@php
$name = $user->name ?? '';
$initial = mb_strtoupper(mb_substr(trim($name) ?: '?', 0, 1));
$avatar = method_exists($user, 'getFirstMediaUrl') ? $user->getFirstMediaUrl('avatar') : '';
@endphp

<div {{ $attributes->class(['flex min-w-0 items-center gap-3']) }}>
    <span class="relative flex size-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-brand-gradient font-display text-base font-bold text-on-brand ring-2 ring-secondary/60">
        @if ($avatar)
            <img src="{{ $avatar }}" alt="" width="40" height="40" class="size-full object-cover" loading="lazy" decoding="async">
        @else
            <span aria-hidden="true">{{ $initial }}</span>
        @endif
    </span>
    <span class="min-w-0 flex-1 text-start" @if ($collapsible) x-bind:class="collapsed && 'sr-only'" @endif>
        <span dir="auto" class="block truncate text-sm font-semibold text-strong">{{ $name }}</span>
        <span dir="auto" class="block truncate text-xs text-muted">{{ $user->email ?? '' }}</span>
    </span>
</div>
