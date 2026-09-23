@props(['name', 'maxWidth' => 'md'])

@php
$maxWidths = ['sm' => 'sm:max-w-sm', 'md' => 'sm:max-w-md', 'lg' => 'sm:max-w-lg', 'xl' => 'sm:max-w-xl'];
@endphp

<div
    x-data="{ show: false }"
    x-on:open-modal.window="$event.detail === '{{ $name }}' && (show = true)"
    x-on:close-modal.window="$event.detail === '{{ $name }}' && (show = false)"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
>
    <div x-show="show" x-transition.opacity class="fixed inset-0 bg-slate-900/50" x-on:click="show = false"></div>

    <div
        x-show="show"
        x-transition
        class="relative w-full {{ $maxWidths[$maxWidth] }} rounded-xl bg-white dark:bg-surface p-6 shadow-xl"
    >
        {{ $slot }}
    </div>
</div>
