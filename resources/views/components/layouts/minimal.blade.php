@props(['centered' => null])

@php
$isCentered = $centered ?? request()->routeIs('newsletter.confirm', 'consultations.signed-view', 'certificates.verify.form');
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), config('app.rtl_locales')) ? 'rtl' : 'ltr' }}">
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
<body class="min-h-dvh-full bg-page font-sans text-body antialiased" x-data x-init="if (window.__azNav) $nextTick(() => document.getElementById('main')?.focus({ preventScroll: true })); window.__azNav = true">
    <a href="#main" class="sr-only rounded-lg bg-primary px-4 py-2 text-on-brand focus:not-sr-only focus:absolute focus:start-4 focus:top-4 focus:z-top">{{ __('polish_shell.skip_to_content') }}</a>

    <x-app.auth-split :centered="$isCentered">
        {{ $slot }}
    </x-app.auth-split>

    <x-loading-bar />

    <x-toast />
    @livewireScripts
</body>
</html>
