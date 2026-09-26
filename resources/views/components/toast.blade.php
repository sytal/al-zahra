@props([])

<div
    x-data="toast"
    x-on:toast.window="add($event.detail)"
    role="region"
    aria-label="{{ __('kit_forms.notifications') }}"
    aria-live="polite"
    {{ $attributes->merge(['class' => 'pointer-events-none fixed inset-x-0 bottom-0 z-toast flex flex-col items-center gap-2 p-4 pb-safe sm:items-end sm:p-6']) }}
>
    <template x-for="item in items" :key="item.id">
        <div
            x-transition:enter="transition duration-base ease-enter"
            x-transition:enter-start="translate-y-3 opacity-0 scale-95"
            x-transition:enter-end="translate-y-0 opacity-100 scale-100"
            x-transition:leave="transition duration-fast ease-exit"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 scale-95"
            x-bind:role="item.type === 'danger' || item.type === 'error' ? 'alert' : 'status'"
            class="pointer-events-auto relative flex w-full max-w-sm items-start gap-3 overflow-hidden rounded-2xl border border-subtle bg-surface-raised p-4 shadow-float"
        >
            <span
                class="mt-px grid size-8 shrink-0 place-items-center rounded-full"
                x-bind:class="{
                    'bg-success/15 text-success': item.type === 'success',
                    'bg-danger/15 text-danger': item.type === 'danger' || item.type === 'error',
                    'bg-warning/15 text-warning': item.type === 'warning',
                    'bg-info/15 text-info': item.type === 'info',
                }"
            >
                <x-icon name="check-circle" size="size-5" x-show="item.type === 'success'" aria-hidden="true" />
                <x-icon name="x-circle" size="size-5" x-show="item.type === 'danger' || item.type === 'error'" x-cloak aria-hidden="true" />
                <x-icon name="exclamation-triangle" size="size-5" x-show="item.type === 'warning'" x-cloak aria-hidden="true" />
                <x-icon name="information-circle" size="size-5" x-show="!['success','danger','error','warning'].includes(item.type)" x-cloak aria-hidden="true" />
            </span>
            <p class="min-w-0 flex-1 break-words pt-1 text-sm font-medium leading-snug text-strong" x-text="item.message"></p>
            <button
                type="button"
                x-on:click="dismiss(item.id)"
                aria-label="{{ __('kit_forms.dismiss') }}"
                class="tap-target -my-2 -me-2 shrink-0 rounded-lg text-muted outline-none transition-[color,transform] duration-fast focus-visible:ring-2 focus-visible:ring-brand active:scale-95 [@media(hover:hover)]:hover:text-strong"
            >
                <x-icon name="x-mark" size="size-5" aria-hidden="true" />
            </button>
            <span
                aria-hidden="true"
                class="absolute inset-x-0 bottom-0 h-0.5 bg-brand-secondary ltr:origin-left rtl:origin-right"
                x-init="if (item.duration > 0 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) $el.animate([{ transform: 'scaleX(1)' }, { transform: 'scaleX(0)' }], { duration: item.duration, easing: 'linear', fill: 'forwards' })"
            ></span>
        </div>
    </template>
</div>
