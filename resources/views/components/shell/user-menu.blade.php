@php
$locale = app()->getLocale();
$user = auth()->user();
$initial = mb_strtoupper(mb_substr(trim($user->name ?? '?'), 0, 1));
@endphp

<div x-data="{ open: false }" x-on:click.outside="open = false" x-on:keydown.escape="open = false" class="relative">
    <button
        type="button"
        x-on:click="open = !open"
        aria-haspopup="true"
        x-bind:aria-expanded="open.toString()"
        class="flex min-h-11 items-center gap-2 rounded-full border bg-surface-raised ps-1 pe-3 transition duration-200 hover:border-strong focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary active:scale-[0.98]"
    >
        <span class="grid size-9 place-items-center rounded-full bg-brand-primary text-sm font-semibold text-on-brand" aria-hidden="true">{{ $initial }}</span>
        <span class="sr-only">{{ __('shell_public.account_menu') }}</span>
        <span class="hidden max-w-28 truncate text-sm font-medium text-strong 2xl:block">{{ $user->name }}</span>
        <x-icon name="chevron-down" class="size-3.5 text-muted transition-transform duration-200" x-bind:class="open ? 'rotate-180' : ''" />
    </button>

    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition duration-150 ease-out"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition duration-100 ease-in"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute end-0 top-full z-dropdown mt-2 w-60 origin-top rounded-2xl border bg-surface-raised p-1.5 shadow-float"
    >
        <div class="border-b border-subtle px-3 pb-2 pt-1.5">
            <p class="truncate text-sm font-semibold text-strong">{{ $user->name }}</p>
            <p class="truncate text-xs text-muted">{{ $user->email }}</p>
        </div>
        <div class="pt-1.5">
            <a href="{{ route('dashboard', $locale) }}" wire:navigate x-on:click="open = false" class="flex min-h-11 items-center gap-3 rounded-xl px-3 text-sm text-body transition hover:bg-tint hover:text-strong focus-visible:bg-tint focus-visible:outline-none">
                <x-icon name="squares-2x2" class="size-4 text-brand-primary" />{{ __('nav.dashboard') }}
            </a>
            <a href="{{ route('dashboard.profile.edit', $locale) }}" wire:navigate x-on:click="open = false" class="flex min-h-11 items-center gap-3 rounded-xl px-3 text-sm text-body transition hover:bg-tint hover:text-strong focus-visible:bg-tint focus-visible:outline-none">
                <x-icon name="user-circle" class="size-4 text-brand-primary" />{{ __('shell_public.profile') }}
            </a>
            <form method="POST" action="{{ route('logout', $locale) }}">
                @csrf
                <button type="submit" class="flex min-h-11 w-full items-center gap-3 rounded-xl px-3 text-start text-sm text-body transition hover:bg-tint hover:text-strong focus-visible:bg-tint focus-visible:outline-none">
                    <x-icon name="arrow-right-on-rectangle" class="size-4 text-brand-primary rtl:-scale-x-100" />{{ __('nav.logout') }}
                </button>
            </form>
        </div>
    </div>
</div>
