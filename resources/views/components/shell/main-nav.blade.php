@props(['items' => []])

@php
$locale = app()->getLocale();
@endphp

<header
    x-data="stickyHeader"
    x-bind:data-scrolled="scrolled"
    class="group/header sticky top-0 z-header border-b border-transparent bg-surface/80 backdrop-blur-md transition-[background-color,border-color,box-shadow] duration-base data-[scrolled=true]:border-subtle data-[scrolled=true]:bg-surface/90 data-[scrolled=true]:shadow-soft print:hidden"
>
    <div class="mx-auto flex h-16 max-w-7xl items-center gap-3 px-4 transition-[height] duration-base ease-enter group-data-[scrolled=true]/header:h-14 sm:px-6 lg:gap-6 lg:px-8">
        <a href="{{ route('home', $locale) }}" wire:navigate class="shrink-0 rounded-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-primary" aria-label="{{ config('app.name') }}">
            <x-brand.logo style="direction:ltr" class="me-2 h-9 w-auto overflow-visible transition-[height] duration-base group-data-[scrolled=true]/header:h-8" />
        </a>

        <nav aria-label="{{ __('shell_public.primary_nav') }}" class="hidden min-w-0 flex-1 justify-center lg:flex">
            <ul class="flex items-center gap-0.5 xl:gap-1">
                @foreach ($items as $item)
                    <li><x-nav-link :href="$item['url']" :active="request()->routeIs($item['match'])" wire:navigate>{{ $item['label'] }}</x-nav-link></li>
                @endforeach
            </ul>
        </nav>

        <div class="ms-auto flex items-center gap-2 lg:ms-0">
            @guest
                <a href="{{ route('login', $locale) }}" wire:navigate class="hidden min-h-11 items-center rounded-full px-4 text-sm font-medium text-body/80 transition hover:bg-tint hover:text-strong focus-visible:outline focus-visible:outline-2 focus-visible:outline-brand-primary xl:inline-flex">{{ __('nav.login') }}</a>
                <a href="{{ route('login', $locale) }}" wire:navigate class="tap-target hidden rounded-full text-body/80 transition hover:bg-tint hover:text-strong focus-visible:outline focus-visible:outline-2 focus-visible:outline-brand-primary lg:inline-flex xl:hidden" aria-label="{{ __('nav.login') }}"><x-icon name="user-circle" class="size-6" /></a>
                <a href="{{ route('register', $locale) }}" wire:navigate class="hidden min-h-11 items-center rounded-full border border-strong px-4 text-sm font-medium text-strong transition hover:border-brand-primary hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary 2xl:inline-flex">{{ __('nav.register') }}</a>
            @else
                <div class="hidden lg:block"><x-shell.user-menu /></div>
            @endguest

            <a href="{{ route('consultation.show', $locale) }}" wire:navigate class="shimmer-sweep hidden min-h-11 items-center gap-2 rounded-full bg-brand-primary px-5 text-sm font-semibold text-on-brand shadow-soft transition duration-200 hover:bg-brand-hover hover:shadow-glow focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary active:scale-[0.98] sm:inline-flex">
                <span class="star-mark text-[0.7rem]" aria-hidden="true"></span>
                <span class="whitespace-nowrap">{{ __('shell_public.book_consultation') }}</span>
            </a>

            <button
                type="button"
                x-data
                x-on:click="$dispatch('shell-drawer', true)"
                aria-haspopup="dialog"
                aria-controls="mobile-nav"
                data-drawer-trigger
                class="tap-target rounded-full border bg-surface-raised text-strong transition duration-200 hover:border-strong focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary active:scale-[0.96] lg:hidden"
            >
                <span class="sr-only">{{ __('shell_public.open_menu') }}</span>
                <x-icon name="bars-3" class="size-6" />
            </button>
        </div>
    </div>
</header>

<x-shell.mobile-drawer :items="$items" />
