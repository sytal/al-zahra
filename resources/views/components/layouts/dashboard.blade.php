@props(['title' => null, 'crumbs' => []])

@php
$locale = app()->getLocale();
$links = [
    ['route' => 'dashboard', 'match' => ['dashboard'], 'label' => __('dashboard.nav_dashboard'), 'icon' => 'squares-2x2'],
    ['route' => 'dashboard.courses.index', 'match' => ['dashboard.courses.*'], 'label' => __('dashboard.nav_my_courses'), 'icon' => 'academic-cap'],
    ['route' => 'dashboard.consultations.index', 'match' => ['dashboard.consultations.*'], 'label' => __('dashboard.nav_consultations'), 'icon' => 'chat-bubble-left-right'],
    ['route' => 'dashboard.certificates.index', 'match' => ['dashboard.certificates.*'], 'label' => __('dashboard.nav_certificates'), 'icon' => 'document-check'],
    ['route' => 'dashboard.profile.edit', 'match' => ['dashboard.profile.*'], 'label' => __('dashboard.nav_profile'), 'icon' => 'user-circle'],
];
$links = array_map(fn ($l) => $l + ['active' => request()->routeIs(...$l['match'])], $links);
$activeLabel = collect($links)->firstWhere('active', true)['label'] ?? null;
$topTitle = $title ?? $activeLabel ?? __('shell_app.page_default');
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
<body
    class="flex min-h-dvh-full bg-page font-sans text-body antialiased"
    x-data="{
        collapsed: localStorage.getItem('az-sidebar') === '1',
        toggleSidebar() { this.collapsed = !this.collapsed; localStorage.setItem('az-sidebar', this.collapsed ? '1' : '0') },
    }"
    x-init="if (window.__azNav) $nextTick(() => $refs.main?.focus({ preventScroll: true })); window.__azNav = true"
>
    <a href="#main" class="sr-only rounded-lg bg-primary px-4 py-2 text-on-brand focus:not-sr-only focus:absolute focus:start-4 focus:top-4 focus:z-top">{{ __('polish_shell.skip_to_content') }}</a>

    <x-app.sidebar :links="$links" />

    <div class="flex min-h-dvh-full min-w-0 flex-1 flex-col">
        <x-app.topbar :title="$topTitle" />

        @if (isset($breadcrumbs) || count($crumbs))
            <div class="mx-auto w-full max-w-6xl px-4 pt-4 md:px-6">
                @isset($breadcrumbs)
                    {{ $breadcrumbs }}
                @else
                    <x-breadcrumbs :items="$crumbs" />
                @endisset
            </div>
        @endif

        <main id="main" x-ref="main" tabindex="-1" class="min-w-0 flex-1 focus:outline-none">
            {{ $slot }}
        </main>

        <footer class="mt-8 border-t border-subtle pb-[calc(5rem+env(safe-area-inset-bottom))] md:pb-0">
            <div class="mx-auto flex w-full max-w-6xl flex-col gap-2 px-4 py-5 text-xs text-muted sm:flex-row sm:items-center sm:justify-between md:px-6">
                <p class="flex items-center gap-2"><span class="star-mark text-xs" aria-hidden="true"></span><span>&copy; {{ now()->year }} {{ config('app.name') }}. {{ __('shell_app.rights') }}</span></p>
                <p class="hidden sm:block">{{ __('shell_app.footer_note') }}</p>
                <a href="{{ route('contact.show', $locale) }}" wire:navigate class="link-underline w-fit text-body hover:text-strong">{{ __('nav.contact') }}</a>
            </div>
        </footer>
    </div>

    <x-app.bottom-nav :links="$links" />

    <x-loading-bar />

    <x-toast />
    @livewireScripts
</body>
</html>
