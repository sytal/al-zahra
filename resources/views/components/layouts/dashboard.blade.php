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
<body class="flex min-h-screen bg-surface font-sans text-ink antialiased" x-data="{ sidebarOpen: false }">
    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-cloak x-on:click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/50 md:hidden"></div>

    <aside
        class="fixed inset-y-0 start-0 z-50 w-64 -translate-x-full border-e border-ink/10 bg-white dark:bg-surface transition-transform duration-200 ease-in-out md:static md:translate-x-0"
        x-bind:class="sidebarOpen && '!translate-x-0'"
    >
        <div class="flex h-16 items-center px-6">
            <a href="{{ route('home', app()->getLocale()) }}" wire:navigate class="text-lg font-semibold text-ink">
                {{ config('app.name') }}
            </a>
        </div>

        <nav class="space-y-1 px-3">
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
                    class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition duration-200 ease-in-out {{ $active ? 'bg-brand-primary/10 text-brand-primary' : 'text-ink/70 hover:bg-surface hover:text-ink' }}"
                >
                    <x-icon :name="$link['icon']" class="size-5" />
                    {{ $link['label'] }}
                </a>
            @endforeach

            <form method="POST" action="{{ route('logout', app()->getLocale()) }}">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-ink/70 transition duration-200 ease-in-out hover:bg-surface hover:text-ink">
                    <x-icon name="arrow-right-on-rectangle" class="size-5" />
                    {{ __('dashboard.nav_logout') }}
                </button>
            </form>
        </nav>
    </aside>

    <div class="flex min-h-screen flex-1 flex-col md:ms-0">
        <header class="flex h-16 items-center justify-between border-b border-ink/10 bg-white dark:bg-surface px-4 md:justify-end">
            <button type="button" x-on:click="sidebarOpen = true" class="rounded-sm bg-surface p-2.5 text-ink/70 md:hidden">
                <span class="sr-only">{{ __('dashboard.toggle_menu') }}</span>
                <x-icon name="bars-3" class="size-5" />
            </button>

            <div class="flex items-center gap-3">
                <x-theme-toggle />
                <x-language-switcher />
            </div>
        </header>

        <main class="flex-1">
            {{ $slot }}
        </main>

        <x-footer :minimal="true" />
    </div>

    <x-loading-bar />

    @livewireScripts
</body>
</html>
