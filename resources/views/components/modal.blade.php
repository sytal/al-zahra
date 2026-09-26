@props(['name', 'maxWidth' => 'md', 'title' => null, 'show' => false, 'closeable' => true])

@php
$maxWidths = ['sm' => 'sm:max-w-sm', 'md' => 'sm:max-w-md', 'lg' => 'sm:max-w-lg', 'xl' => 'sm:max-w-xl', '2xl' => 'sm:max-w-2xl'];
$titleId = 'modal-' . $name . '-title';
@endphp

<div
    x-data="{ show: @js((bool) $show) }"
    x-on:open-modal.window="$event.detail === @js($name) && (show = true)"
    x-on:close-modal.window="$event.detail === @js($name) && (show = false)"
    @if ($closeable) x-on:keydown.escape.window="show && (show = false)" @endif
    x-show="show"
    x-cloak
    class="fixed inset-0 z-modal flex items-end justify-center sm:items-center sm:p-6"
    @if ($title) role="dialog" aria-modal="true" aria-labelledby="{{ $titleId }}" @else role="dialog" aria-modal="true" @endif
>
    <div
        x-show="show"
        x-transition:enter="transition duration-base ease-enter"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition duration-fast ease-exit"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-deep-950/60 backdrop-blur-sm"
        @if ($closeable) x-on:click="show = false" @endif
        aria-hidden="true"
    ></div>

    <div
        x-show="show"
        x-trap.noscroll="show"
        x-transition:enter="transition duration-base ease-enter"
        x-transition:enter-start="translate-y-full opacity-0 sm:translate-y-3 sm:scale-95"
        x-transition:enter-end="translate-y-0 opacity-100 sm:scale-100"
        x-transition:leave="transition duration-fast ease-exit"
        x-transition:leave-start="translate-y-0 opacity-100 sm:scale-100"
        x-transition:leave-end="translate-y-full opacity-0 sm:translate-y-3 sm:scale-95"
        class="relative flex max-h-[92dvh] w-full flex-col overflow-hidden rounded-t-3xl border border-subtle bg-surface-raised shadow-float sm:max-h-[85dvh] sm:rounded-3xl {{ $maxWidths[$maxWidth] ?? $maxWidths['md'] }}"
    >
        <span aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-brand-secondary to-transparent"></span>
        <span aria-hidden="true" class="mx-auto mt-2.5 h-1 w-10 shrink-0 rounded-full bg-subtle/50 sm:hidden"></span>

        @if ($title || $closeable)
            <div class="flex shrink-0 items-start justify-between gap-4 px-5 pb-2 pt-4 sm:px-7 sm:pt-6">
                @if ($title)
                    <h2 id="{{ $titleId }}" class="min-w-0 break-words font-display text-xl font-semibold leading-tight text-strong">{{ $title }}</h2>
                @else
                    <span></span>
                @endif
                @if ($closeable)
                    <button
                        type="button"
                        x-on:click="show = false"
                        aria-label="{{ __('kit_forms.close') }}"
                        class="tap-target -me-2 -mt-1 shrink-0 rounded-xl text-muted outline-none transition-[color,background-color,transform] duration-fast focus-visible:ring-4 focus-visible:ring-brand/30 active:scale-95 [@media(hover:hover)]:hover:bg-surface-sunken [@media(hover:hover)]:hover:text-strong"
                    >
                        <x-icon name="x-mark" size="size-6" aria-hidden="true" />
                    </button>
                @endif
            </div>
        @endif

        <div class="min-h-0 flex-1 overflow-y-auto overscroll-contain px-5 py-4 sm:px-7 {{ isset($footer) ? '' : 'pb-safe' }}">
            {{ $slot }}
        </div>

        @isset($footer)
            <div class="flex shrink-0 flex-col-reverse gap-2 border-t border-subtle bg-surface-sunken px-5 py-4 pb-safe sm:flex-row sm:justify-end sm:px-7">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
