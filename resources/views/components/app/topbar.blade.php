@props(['title' => null])

@php $locale = app()->getLocale(); @endphp

<header class="pt-safe ps-safe pe-safe sticky top-0 z-header border-b border-subtle bg-page/85 backdrop-blur [@media(max-height:480px)]:relative">
    <div class="flex h-14 items-center gap-3 px-4 md:h-16 md:px-6">
        <a href="{{ route('dashboard', $locale) }}" wire:navigate class="focus-ring inline-flex shrink-0 items-center rounded-lg md:hidden" aria-label="{{ config('app.name') }}">
            <x-brand.logo variant="mark" class="size-8" />
        </a>

        <span class="flex-1 sm:hidden"></span>
        <p class="hidden min-w-0 flex-1 truncate font-display sm:block text-lg font-semibold text-strong md:text-xl">{{ $title }}</p>

        <div class="flex shrink-0 items-center gap-1" role="group" aria-label="{{ __('shell_app.controls') }}">
            <x-theme-toggle />
            <x-language-switcher />

            <div
                x-data="{ open: false }"
                x-on:click.outside="open = false"
                x-on:keydown.escape="if (open) { open = false; $refs.trigger.focus() }"
                class="relative"
            >
                <button
                    type="button"
                    x-ref="trigger"
                    x-on:click="open = !open; if (open) $nextTick(() => $refs.panel.querySelector('a, button')?.focus())"
                    x-bind:aria-expanded="open.toString()"
                    aria-haspopup="true"
                    aria-label="{{ __('shell_app.account_menu') }}"
                    class="focus-ring tap-target ms-1 rounded-full transition duration-fast active:scale-95"
                >
                    <x-app.user-card :user="auth()->user()" class="[&>span:last-child]:hidden" />
                </button>

                <div
                    x-ref="panel"
                    x-show="open"
                    x-cloak
                    x-transition:enter="transition duration-base ease-enter"
                    x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition duration-fast ease-exit"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    role="menu"
                    class="absolute end-0 top-full z-dropdown mt-2 w-64 max-w-[calc(100vw-2rem)] ltr:origin-top-right rtl:origin-top-left overflow-hidden rounded-2xl border border-subtle bg-surface-raised shadow-float"
                >
                    <div class="border-b border-subtle p-3">
                        <p class="mb-2 text-xs text-muted">{{ __('shell_app.signed_in_as') }}</p>
                        <x-app.user-card :user="auth()->user()" />
                    </div>
                    <div class="p-1.5">
                        <a role="menuitem" href="{{ route('dashboard.profile.edit', $locale) }}" wire:navigate x-on:click="open = false" class="focus-ring flex min-h-11 items-center gap-3 rounded-xl px-3 text-sm font-medium text-body transition duration-fast hover:bg-tint hover:text-strong">
                            <x-icon name="user-circle" class="size-5" />{{ __('dashboard.nav_profile') }}
                        </a>
                        <a role="menuitem" href="{{ route('home', $locale) }}" wire:navigate x-on:click="open = false" class="focus-ring flex min-h-11 items-center gap-3 rounded-xl px-3 text-sm font-medium text-body transition duration-fast hover:bg-tint hover:text-strong">
                            <x-icon name="globe-alt" class="size-5" />{{ __('shell_app.view_site') }}
                        </a>
                        <form method="POST" action="{{ route('logout', $locale) }}">
                            @csrf
                            <button role="menuitem" type="submit" class="focus-ring flex min-h-11 w-full items-center gap-3 rounded-xl px-3 text-sm font-medium text-body transition duration-fast hover:bg-danger/10 hover:text-strong">
                                <x-icon name="arrow-right-on-rectangle" class="size-5 rtl:-scale-x-100" />{{ __('dashboard.nav_logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
