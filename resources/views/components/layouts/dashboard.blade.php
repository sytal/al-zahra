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
<body class="flex min-h-screen bg-surface font-sans text-ink antialiased" x-data="{ sidebarOpen: false, narrow: window.matchMedia('(max-width: 767.98px)').matches }" x-init="window.matchMedia('(max-width: 767.98px)').addEventListener('change', (e) => { narrow = e.matches; if (!e.matches) sidebarOpen = false })" x-on:keydown.escape.window="if (sidebarOpen) { sidebarOpen = false; $refs.menuButton.focus() }">
    <a href="#main" class="sr-only rounded-lg bg-brand-primary px-4 py-2 text-on-brand focus:not-sr-only focus:absolute focus:start-4 focus:top-4 focus:z-50">{{ __('polish_shell.skip_to_content') }}</a>
    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-cloak x-transition.opacity.duration.200ms x-on:click="sidebarOpen = false" aria-hidden="true" class="fixed inset-0 z-40 bg-slate-900/50 md:hidden"></div>

    <aside
        id="sidebar" class="fixed inset-y-0 start-0 z-50 w-64 -translate-x-full overflow-y-auto border-e border-ink/10 bg-white dark:bg-surface transition-transform duration-200 ease-in-out rtl:translate-x-full md:static md:shrink-0 md:translate-x-0 md:rtl:translate-x-0"
        x-bind:class="sidebarOpen && '!translate-x-0'"
        x-bind:inert="narrow && !sidebarOpen"
        x-effect="if (narrow && sidebarOpen) $nextTick(() => $el.querySelector('a')?.focus())"
        x-on:click="if ($event.target.closest('a')) sidebarOpen = false"
    >
        <div class="flex h-16 items-center px-6">
            <a href="{{ route('home', app()->getLocale()) }}" wire:navigate class="rounded-sm text-lg font-semibold text-ink focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">
                {{ config('app.name') }}
            </a>
        </div>

        <nav aria-label="{{ __('polish_shell.dashboard_nav') }}" class="space-y-1 px-3">
            @php
            $links = [
                ['route' => 'dashboard', 'label' => __('dashboard.nav_dashboard'), 'icon' => 'squares-2x2'],
                ['route' => 'dashboard.courses.index', 'label' => __('dashboard.nav_my_courses'), 'icon' => 'academic-cap'],
                ['route' => 'dashboard.consultations.index', 'label' => __('dashboard.nav_consultations'), 'icon' => 'chat-bubble-left-right'],
                ['route' => 'dashboard.certificates.index', 'label' => __('dashboard.nav_certificates'), 'icon' => 'document-check'],
                ['route' => 'dashboard.profile.edit', 'label' => __('dashboard.nav_profile'), 'icon' => 'user-circle'],
            ];
            @endphp

            @foreach ($links as $link)
                @php $active = request()->routeIs($link['route']); @endphp
                <a
                    href="{{ route($link['route'], app()->getLocale()) }}"
                    wire:navigate
                    @if ($active) aria-current="page" @endif
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition duration-200 ease-in-out focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary {{ $active ? 'bg-brand-primary/10 text-brand-primary' : 'text-ink/70 hover:bg-ink/5 hover:text-ink' }}"
                >
                    <x-icon :name="$link['icon']" class="size-5" />
                    {{ $link['label'] }}
                </a>
            @endforeach

            <form method="POST" action="{{ route('logout', app()->getLocale()) }}">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-ink/70 transition duration-200 ease-in-out hover:bg-ink/5 hover:text-ink focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">
                    <x-icon name="arrow-right-on-rectangle" class="size-5 rtl:-scale-x-100" />
                    {{ __('dashboard.nav_logout') }}
                </button>
            </form>
        </nav>
    </aside>

    <div class="flex min-h-screen flex-1 flex-col min-w-0">
        <header class="flex h-16 items-center justify-between border-b border-ink/10 bg-white dark:bg-surface px-4 md:justify-end">
            <button type="button" x-ref="menuButton" x-on:click="sidebarOpen = true" x-bind:aria-expanded="sidebarOpen.toString()" aria-controls="sidebar" class="rounded-lg bg-surface p-2.5 text-ink/70 md:hidden focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">
                <span class="sr-only">{{ __('dashboard.toggle_menu') }}</span>
                <x-icon name="bars-3" class="size-5" />
            </button>

            <div class="flex items-center gap-3">
                <x-theme-toggle />
                <x-language-switcher />
            </div>
        </header>

        <main id="main" class="flex-1">
            {{ $slot }}
        </main>

        <x-footer :minimal="true" />
    </div>

    <x-loading-bar />

    @livewireScripts
</body>
</html>
