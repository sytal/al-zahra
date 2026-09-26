@props(['variant' => 'info', 'title' => null, 'dismissible' => false, 'icon' => null])

@php
$map = [
    'info' => ['icon' => 'information-circle', 'tint' => 'bg-info/10 border-info/30', 'bar' => 'bg-info', 'fg' => 'text-info', 'role' => 'status'],
    'success' => ['icon' => 'check-circle', 'tint' => 'bg-success/10 border-success/30', 'bar' => 'bg-success', 'fg' => 'text-success', 'role' => 'status'],
    'warning' => ['icon' => 'exclamation-triangle', 'tint' => 'bg-warning/10 border-warning/30', 'bar' => 'bg-warning', 'fg' => 'text-warning', 'role' => 'alert'],
    'danger' => ['icon' => 'x-circle', 'tint' => 'bg-danger/10 border-danger/30', 'bar' => 'bg-danger', 'fg' => 'text-danger', 'role' => 'alert'],
    'neutral' => ['icon' => 'sparkles', 'tint' => 'bg-surface-sunken border-subtle', 'bar' => 'bg-brand-secondary', 'fg' => 'text-secondary-text', 'role' => 'status'],
];
$v = $map[$variant] ?? $map['info'];
@endphp

<div
    x-data="{ open: true }"
    x-show="open"
    x-transition:leave="transition duration-base ease-exit"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-1"
    role="{{ $v['role'] }}"
    {{ $attributes->merge(['class' => 'relative flex items-start gap-3 overflow-hidden rounded-2xl border p-4 ps-5 ' . $v['tint']]) }}
>
    <span aria-hidden="true" class="absolute inset-y-0 start-0 w-1.5 {{ $v['bar'] }}"></span>
    <x-icon :name="$icon ?? $v['icon']" size="size-6" class="mt-px shrink-0 {{ $v['fg'] }}" aria-hidden="true" />
    <div class="min-w-0 flex-1 break-words text-sm leading-relaxed text-body">
        @if ($title)<p class="mb-0.5 text-base font-semibold text-strong">{{ $title }}</p>@endif
        {{ $slot }}
    </div>
    @if ($dismissible)
        <button
            type="button"
            x-on:click="open = false"
            aria-label="{{ __('kit_forms.dismiss') }}"
            class="tap-target -my-2 -me-2 shrink-0 rounded-lg text-muted outline-none transition-[color,transform] duration-fast focus-visible:ring-2 focus-visible:ring-brand active:scale-95 [@media(hover:hover)]:hover:text-strong"
        >
            <x-icon name="x-mark" size="size-5" aria-hidden="true" />
        </button>
    @endif
</div>
