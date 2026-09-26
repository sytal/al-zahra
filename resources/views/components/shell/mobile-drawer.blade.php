@props(['items' => []])

@php
$locale = app()->getLocale();
@endphp

<div
    id="mobile-nav"
    x-data="{
        open: false,
        trigger: null,
        focusables() {
            return [...this.$refs.panel.querySelectorAll('a[href], button:not([disabled]), input:not([disabled])')].filter((el) => el.offsetParent !== null);
        },
        trap(e) {
            if (e.key !== 'Tab' || !this.open) return;
            const list = this.focusables();
            if (!list.length) return;
            const first = list[0];
            const last = list[list.length - 1];
            if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
            else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
            else if (!this.$refs.panel.contains(document.activeElement)) { e.preventDefault(); first.focus(); }
        },
        init() {
            this.$watch('open', (value) => {
                document.documentElement.classList.toggle('overflow-hidden', value);
                if (value) {
                    this.trigger = document.activeElement;
                    this.$nextTick(() => this.$refs.close.focus());
                } else if (this.trigger && document.contains(this.trigger)) {
                    this.trigger.focus();
                }
            });
        },
        destroy() { document.documentElement.classList.remove('overflow-hidden'); },
    }"
    x-on:shell-drawer.window="open = $event.detail"
    x-on:keydown.escape.window="open = false"
    x-on:keydown.tab.window="trap($event)"
    x-on:resize.window="if (window.innerWidth >= 1024) open = false"
    x-show="open"
    x-cloak
    role="dialog"
    aria-modal="true"
    aria-label="{{ __('polish_shell.mobile_nav') }}"
    class="fixed inset-0 z-overlay lg:hidden print:hidden"
>
    <div
        x-show="open"
        x-transition:enter="transition-opacity duration-base ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-fast ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="open = false"
        class="absolute inset-0 bg-deep-950/60 backdrop-blur-sm"
        aria-hidden="true"
    ></div>

    <div
        x-ref="panel"
        x-show="open"
        x-transition:enter="transition-transform duration-slow ease-enter"
        x-transition:enter-start="ltr:translate-x-full rtl:-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform duration-base ease-exit"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="ltr:translate-x-full rtl:-translate-x-full"
        x-on:click="if ($event.target.closest('a[href]')) open = false"
        class="pt-safe pb-safe ps-safe pe-safe absolute inset-y-0 end-0 flex h-dvh-full w-full max-w-sm flex-col overflow-y-auto overscroll-contain border-s bg-surface shadow-float"
    >
        <div class="relative flex min-h-16 shrink-0 items-center justify-between gap-3 border-b border-subtle px-4">
            <a href="{{ route('home', $locale) }}" wire:navigate class="rounded-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-primary" aria-label="{{ config('app.name') }}">
                <x-brand.logo style="direction:ltr" class="me-2 h-9 w-auto overflow-visible" />
            </a>
            <button type="button" x-ref="close" x-on:click="open = false" class="tap-target rounded-full border bg-surface-raised text-strong transition duration-200 hover:border-strong focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary active:scale-[0.96]">
                <span class="sr-only">{{ __('shell_public.close_menu') }}</span>
                <x-icon name="x-mark" class="size-6" />
            </button>
        </div>

        <div class="flex-1 space-y-6 px-4 py-5">
            <nav aria-label="{{ __('polish_shell.mobile_nav') }}">
                <ul class="space-y-1">
                    @foreach ($items as $item)
                        @php($active = request()->routeIs($item['match']))
                        <li>
                            <x-responsive-nav-link :href="$item['url']" :active="$active" wire:navigate>
                                <x-icon :name="$item['icon']" class="size-5 shrink-0 {{ $active ? 'text-brand-primary' : 'text-muted' }}" />
                                <span class="min-w-0 flex-1">{{ $item['label'] }}</span>
                                <x-icon name="chevron-right" class="size-4 shrink-0 text-subtle rtl:-scale-x-100" />
                            </x-responsive-nav-link>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <a href="{{ route('consultation.show', $locale) }}" wire:navigate class="shimmer-sweep flex min-h-12 w-full items-center justify-center gap-2 rounded-full bg-brand-primary px-5 text-base font-semibold text-on-brand shadow-soft transition duration-200 hover:bg-brand-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary active:scale-[0.98]">
                <span class="star-mark text-xs" aria-hidden="true"></span>
                {{ __('shell_public.book_consultation') }}
            </a>

            @guest
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('login', $locale) }}" wire:navigate class="flex min-h-12 items-center justify-center rounded-full border border-strong px-3 text-center text-sm font-medium text-strong transition hover:border-brand-primary hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary active:scale-[0.98]">{{ __('nav.login') }}</a>
                    <a href="{{ route('register', $locale) }}" wire:navigate class="flex min-h-12 items-center justify-center rounded-full border border-strong px-3 text-center text-sm font-medium text-strong transition hover:border-brand-primary hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary active:scale-[0.98]">{{ __('nav.register') }}</a>
                </div>
            @else
                <div class="space-y-1 rounded-2xl border bg-surface-raised p-2">
                    <p class="truncate px-3 pb-1 pt-2 text-sm font-semibold text-strong">{{ auth()->user()->name }}</p>
                    <a href="{{ route('dashboard', $locale) }}" wire:navigate class="flex min-h-12 items-center gap-3 rounded-xl px-3 text-sm text-body transition hover:bg-tint hover:text-strong focus-visible:outline focus-visible:outline-2 focus-visible:outline-brand-primary"><x-icon name="squares-2x2" class="size-5 text-brand-primary" />{{ __('nav.dashboard') }}</a>
                    <a href="{{ route('dashboard.profile.edit', $locale) }}" wire:navigate class="flex min-h-12 items-center gap-3 rounded-xl px-3 text-sm text-body transition hover:bg-tint hover:text-strong focus-visible:outline focus-visible:outline-2 focus-visible:outline-brand-primary"><x-icon name="user-circle" class="size-5 text-brand-primary" />{{ __('shell_public.profile') }}</a>
                    <form method="POST" action="{{ route('logout', $locale) }}">
                        @csrf
                        <button type="submit" class="flex min-h-12 w-full items-center gap-3 rounded-xl px-3 text-start text-sm text-body transition hover:bg-tint hover:text-strong focus-visible:outline focus-visible:outline-2 focus-visible:outline-brand-primary"><x-icon name="arrow-right-on-rectangle" class="size-5 text-brand-primary rtl:-scale-x-100" />{{ __('nav.logout') }}</button>
                    </form>
                </div>
            @endguest
        </div>

        <div class="shrink-0 space-y-3 border-t border-subtle bg-surface-sunken px-4 py-4">
            <div class="flex items-center justify-between gap-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-muted">{{ __('shell_public.language') }}</p>
                <x-theme-toggle class="border bg-surface-raised" />
            </div>
            <x-language-switcher variant="list" />
        </div>
    </div>
</div>
