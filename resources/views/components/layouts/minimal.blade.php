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
<body class="flex min-h-screen flex-col bg-surface font-sans text-ink antialiased">
    <header class="border-b border-ink/10 bg-white dark:bg-surface">
        <div class="mx-auto flex max-w-3xl items-center justify-between px-4 py-4">
            <a href="{{ route('home', app()->getLocale()) }}" wire:navigate class="text-lg font-semibold text-ink">
                {{ config('app.name') }}
            </a>
            <div class="flex items-center gap-3">
                <x-theme-toggle />
                <x-language-switcher />
            </div>
        </div>
    </header>

    <main class="flex flex-1 items-center justify-center px-4 py-12">
        {{ $slot }}
    </main>

    <x-footer :minimal="true" />

    <x-loading-bar />

    @livewireScripts
</body>
</html>
