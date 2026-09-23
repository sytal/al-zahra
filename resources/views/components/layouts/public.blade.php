<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), config('app.rtl_locales')) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{ $seo ?? '' }}
    @if (!isset($seo))<title>{{ config('app.name') }}</title>@endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-white dark:bg-surface font-sans text-ink antialiased">
    <header class="border-b border-ink/10">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
            <a href="{{ route('home', app()->getLocale()) }}" wire:navigate class="text-lg font-semibold text-ink">
                {{ config('app.name') }}
            </a>

            <nav class="hidden items-center gap-6 md:flex">
                <a href="{{ route('articles.index', app()->getLocale()) }}" wire:navigate class="text-sm text-ink/70 hover:text-brand-primary">{{ __('nav.articles') }}</a>
                <a href="{{ route('courses.index', app()->getLocale()) }}" wire:navigate class="text-sm text-ink/70 hover:text-brand-primary">{{ __('nav.courses') }}</a>
                <a href="{{ route('research.index', app()->getLocale()) }}" wire:navigate class="text-sm text-ink/70 hover:text-brand-primary">{{ __('nav.research') }}</a>
            </nav>

            <x-language-switcher />
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <x-footer />

    @livewireScripts
</body>
</html>
