<div
    x-data="backToTop"
    x-show="visible"
    x-cloak
    x-transition:enter="transition duration-base ease-enter"
    x-transition:enter-start="translate-y-3 opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition duration-fast ease-exit"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="pointer-events-none fixed bottom-[max(1rem,env(safe-area-inset-bottom))] end-[max(1rem,env(safe-area-inset-right))] z-sticky print:hidden"
>
    <button
        type="button"
        x-on:click="top"
        class="pointer-events-auto tap-target size-12 rounded-full border border-brand-secondary/60 bg-brand-primary text-on-brand shadow-float transition duration-200 hover:bg-brand-hover hover:shadow-glow focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-secondary active:scale-[0.94]"
    >
        <span class="sr-only">{{ __('shell_public.back_to_top') }}</span>
        <x-icon name="arrow-up" class="size-5" />
    </button>
</div>
