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
    <header class="border-b border-ink/10 bg-white dark:bg-surface" x-data="{ mobileOpen: false }">
        <div class="mx-auto flex h-16 max-w-6xl items-center gap-8 px-4">
            <a href="{{ route('home', app()->getLocale()) }}" wire:navigate class="block text-lg font-semibold text-ink">
                {{ config('app.name') }}
            </a>

            <div class="flex flex-1 items-center justify-end gap-4 md:justify-between">
                <nav aria-label="Global" class="hidden md:block">
                    <ul class="flex items-center gap-6 text-sm">
                        <li><a href="{{ route('about', app()->getLocale()) }}" wire:navigate class="text-ink/70 transition duration-200 ease-in-out hover:text-brand-primary">{{ __('nav.about') }}</a></li>
                        <li><a href="{{ route('articles.index', app()->getLocale()) }}" wire:navigate class="text-ink/70 transition duration-200 ease-in-out hover:text-brand-primary">{{ __('nav.articles') }}</a></li>
                        <li><a href="{{ route('courses.index', app()->getLocale()) }}" wire:navigate class="text-ink/70 transition duration-200 ease-in-out hover:text-brand-primary">{{ __('nav.courses') }}</a></li>
                        <li><a href="{{ route('research.index', app()->getLocale()) }}" wire:navigate class="text-ink/70 transition duration-200 ease-in-out hover:text-brand-primary">{{ __('nav.research') }}</a></li>
                        <li><a href="{{ route('resources.index', app()->getLocale()) }}" wire:navigate class="text-ink/70 transition duration-200 ease-in-out hover:text-brand-primary">{{ __('nav.resources') }}</a></li>
                        <li><a href="{{ route('contact.show', app()->getLocale()) }}" wire:navigate class="text-ink/70 transition duration-200 ease-in-out hover:text-brand-primary">{{ __('nav.contact') }}</a></li>
                    </ul>
                </nav>

                <div class="flex items-center gap-3">
                    <x-theme-toggle />
                    <x-language-switcher />

                    <button
                        type="button"
                        x-on:click="mobileOpen = !mobileOpen"
                        class="block rounded-sm bg-surface p-2.5 text-ink/70 transition duration-200 ease-in-out hover:text-ink md:hidden"
                    >
                        <span class="sr-only">Toggle menu</span>
                        <x-icon name="bars-3" class="size-5" />
                    </button>
                </div>
            </div>
        </div>

        <nav x-show="mobileOpen" x-collapse x-cloak class="border-t border-ink/10 md:hidden">
            <ul class="space-y-1 px-4 py-3 text-sm">
                <li><a href="{{ route('about', app()->getLocale()) }}" wire:navigate class="block py-1.5 text-ink/70 hover:text-brand-primary">{{ __('nav.about') }}</a></li>
                <li><a href="{{ route('articles.index', app()->getLocale()) }}" wire:navigate class="block py-1.5 text-ink/70 hover:text-brand-primary">{{ __('nav.articles') }}</a></li>
                <li><a href="{{ route('courses.index', app()->getLocale()) }}" wire:navigate class="block py-1.5 text-ink/70 hover:text-brand-primary">{{ __('nav.courses') }}</a></li>
                <li><a href="{{ route('research.index', app()->getLocale()) }}" wire:navigate class="block py-1.5 text-ink/70 hover:text-brand-primary">{{ __('nav.research') }}</a></li>
                <li><a href="{{ route('resources.index', app()->getLocale()) }}" wire:navigate class="block py-1.5 text-ink/70 hover:text-brand-primary">{{ __('nav.resources') }}</a></li>
                <li><a href="{{ route('contact.show', app()->getLocale()) }}" wire:navigate class="block py-1.5 text-ink/70 hover:text-brand-primary">{{ __('nav.contact') }}</a></li>
            </ul>
        </nav>
    </header>

    <main>
        {{ $slot }}
    </main>

    <x-footer />

    <x-loading-bar />

    @livewireScripts
</body>
</html>
