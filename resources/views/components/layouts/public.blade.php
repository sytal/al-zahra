<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), config('app.rtl_locales')) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{ $seo ?? '' }}
    @if (!isset($seo))<title>{{ config('app.name') }}</title>@endif

    <x-theme-init-script />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
</head>
<body class="bg-white dark:bg-surface font-sans text-ink antialiased">
    <a href="#main" class="sr-only rounded-lg bg-brand-primary px-4 py-2 text-white focus:not-sr-only focus:absolute focus:start-4 focus:top-4 focus:z-50">{{ __('polish_shell.skip_to_content') }}</a>
    <header x-on:keydown.escape.window="mobileOpen = false" class="border-b border-ink/10 bg-white dark:bg-surface" x-data="{ mobileOpen: false }">
        <div class="mx-auto flex h-16 max-w-6xl items-center gap-4 px-4 lg:gap-8">
            <a href="{{ route('home', app()->getLocale()) }}" wire:navigate class="block rounded-sm text-lg font-semibold text-ink focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">
                {{ config('app.name') }}
            </a>

            <div class="flex flex-1 items-center justify-end gap-4 md:justify-between">
                <nav aria-label="Global" class="hidden md:block">
                    <ul class="flex items-center gap-4 text-sm whitespace-nowrap lg:gap-6">
                        <li><a href="{{ route('about', app()->getLocale()) }}" wire:navigate class="rounded-sm py-2 whitespace-nowrap text-ink/70 transition duration-200 ease-in-out hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">{{ __('nav.about') }}</a></li>
                        <li><a href="{{ route('articles.index', app()->getLocale()) }}" wire:navigate class="rounded-sm py-2 whitespace-nowrap text-ink/70 transition duration-200 ease-in-out hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">{{ __('nav.articles') }}</a></li>
                        <li><a href="{{ route('courses.index', app()->getLocale()) }}" wire:navigate class="rounded-sm py-2 whitespace-nowrap text-ink/70 transition duration-200 ease-in-out hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">{{ __('nav.courses') }}</a></li>
                        <li><a href="{{ route('research.index', app()->getLocale()) }}" wire:navigate class="rounded-sm py-2 whitespace-nowrap text-ink/70 transition duration-200 ease-in-out hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">{{ __('nav.research') }}</a></li>
                        <li><a href="{{ route('resources.index', app()->getLocale()) }}" wire:navigate class="rounded-sm py-2 whitespace-nowrap text-ink/70 transition duration-200 ease-in-out hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">{{ __('nav.resources') }}</a></li>
                        <li><a href="{{ route('contact.show', app()->getLocale()) }}" wire:navigate class="rounded-sm py-2 whitespace-nowrap text-ink/70 transition duration-200 ease-in-out hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">{{ __('nav.contact') }}</a></li>
                    </ul>
                </nav>

                <div class="flex items-center gap-3">
                    @guest
                        <div class="hidden items-center gap-2 lg:flex">
                            <a href="{{ route('login', app()->getLocale()) }}" wire:navigate class="rounded-lg px-4 py-2 text-sm font-medium text-ink/70 transition duration-200 ease-in-out hover:text-ink focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">
                                {{ __('nav.login') }}
                            </a>
                            <a href="{{ route('register', app()->getLocale()) }}" wire:navigate class="rounded-lg bg-brand-primary px-4 py-2 text-sm font-medium text-white transition duration-200 ease-in-out hover:bg-brand-primary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">
                                {{ __('nav.register') }}
                            </a>
                        </div>
                    @else
                        <div class="hidden items-center gap-2 lg:flex">
                            <a href="{{ route('dashboard', app()->getLocale()) }}" wire:navigate class="rounded-lg bg-brand-primary px-4 py-2 text-sm font-medium text-white transition duration-200 ease-in-out hover:bg-brand-primary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">
                                {{ __('nav.dashboard') }}
                            </a>
                            <form method="POST" action="{{ route('logout', app()->getLocale()) }}">
                                @csrf
                                <button type="submit" class="rounded-lg px-4 py-2 text-sm font-medium text-ink/70 transition duration-200 ease-in-out hover:text-ink focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">
                                    {{ __('nav.logout') }}
                                </button>
                            </form>
                        </div>
                    @endguest

                    <x-theme-toggle />
                    <x-language-switcher />

                    <button
                        type="button"
                        x-on:click="mobileOpen = !mobileOpen"
                        x-bind:aria-expanded="mobileOpen.toString()"
                        aria-controls="mobile-nav"
                        class="block rounded-lg bg-surface p-2.5 text-ink/70 transition duration-200 ease-in-out hover:text-ink md:hidden focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary"
                    >
                        <span class="sr-only">{{ __('polish_shell.toggle_menu') }}</span>
                        <x-icon name="bars-3" class="size-5" />
                    </button>
                </div>
            </div>
        </div>

        <nav id="mobile-nav" aria-label="{{ __('polish_shell.mobile_nav') }}" x-show="mobileOpen" x-collapse x-cloak x-on:click="if ($event.target.closest('a')) mobileOpen = false" class="border-t border-ink/10 md:hidden">
            <ul class="space-y-1 px-4 py-3 text-sm">
                <li><a href="{{ route('about', app()->getLocale()) }}" wire:navigate class="block rounded-sm py-2.5 text-ink/70 hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">{{ __('nav.about') }}</a></li>
                <li><a href="{{ route('articles.index', app()->getLocale()) }}" wire:navigate class="block rounded-sm py-2.5 text-ink/70 hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">{{ __('nav.articles') }}</a></li>
                <li><a href="{{ route('courses.index', app()->getLocale()) }}" wire:navigate class="block rounded-sm py-2.5 text-ink/70 hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">{{ __('nav.courses') }}</a></li>
                <li><a href="{{ route('research.index', app()->getLocale()) }}" wire:navigate class="block rounded-sm py-2.5 text-ink/70 hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">{{ __('nav.research') }}</a></li>
                <li><a href="{{ route('resources.index', app()->getLocale()) }}" wire:navigate class="block rounded-sm py-2.5 text-ink/70 hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">{{ __('nav.resources') }}</a></li>
                <li><a href="{{ route('contact.show', app()->getLocale()) }}" wire:navigate class="block rounded-sm py-2.5 text-ink/70 hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">{{ __('nav.contact') }}</a></li>
                <li class="mt-2 border-t border-ink/10 pt-2">
                    @guest
                        <a href="{{ route('login', app()->getLocale()) }}" wire:navigate class="block rounded-sm py-2.5 text-ink/70 hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">{{ __('nav.login') }}</a>
                        <a href="{{ route('register', app()->getLocale()) }}" wire:navigate class="block rounded-sm py-2.5 text-ink/70 hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">{{ __('nav.register') }}</a>
                    @else
                        <a href="{{ route('dashboard', app()->getLocale()) }}" wire:navigate class="block rounded-sm py-2.5 text-ink/70 hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">{{ __('nav.dashboard') }}</a>
                        <form method="POST" action="{{ route('logout', app()->getLocale()) }}">
                            @csrf
                            <button type="submit" class="block w-full rounded-sm py-2.5 text-start text-ink/70 hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">{{ __('nav.logout') }}</button>
                        </form>
                    @endguest
                </li>
            </ul>
        </nav>
    </header>

    <main id="main">
        {{ $slot }}
    </main>

    <x-footer />

    <x-loading-bar />

    @livewireScripts
</body>
</html>
