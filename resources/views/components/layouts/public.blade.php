@php
$locale = app()->getLocale();
$navItems = [
    ['url' => route('about', $locale), 'match' => 'about', 'label' => __('nav.about'), 'icon' => 'information-circle'],
    ['url' => route('articles.index', $locale), 'match' => 'articles.*', 'label' => __('nav.articles'), 'icon' => 'newspaper'],
    ['url' => route('courses.index', $locale), 'match' => 'courses.*', 'label' => __('nav.courses'), 'icon' => 'academic-cap'],
    ['url' => route('research.index', $locale), 'match' => 'research.*', 'label' => __('nav.research'), 'icon' => 'beaker'],
    ['url' => route('resources.index', $locale), 'match' => 'resources.*', 'label' => __('nav.resources'), 'icon' => 'book-open'],
    ['url' => route('contact.show', $locale), 'match' => 'contact.*', 'label' => __('nav.contact'), 'icon' => 'envelope'],
];
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ in_array($locale, config('app.rtl_locales')) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{ $seo ?? '' }}
    @if (!isset($seo))<title>{{ config('app.name') }}</title>@endif

    <x-theme-init-script />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
</head>
<body class="min-h-dvh-full bg-page font-sans text-body antialiased">
    <a href="#main" class="sr-only rounded-full bg-brand-primary px-5 py-3 font-medium text-on-brand shadow-float focus:not-sr-only focus:fixed focus:start-4 focus:top-4 focus:z-top focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-secondary">{{ __('polish_shell.skip_to_content') }}</a>

    <x-loading-bar />

    <x-shell.top-bar />

    <x-shell.main-nav :items="$navItems" />

    <main id="main" class="min-w-0 scroll-mt-24 focus:outline-none" tabindex="-1">
        {{ $slot }}
    </main>

    <x-footer />

    <x-shell.back-to-top />

    @livewireScripts
</body>
</html>
