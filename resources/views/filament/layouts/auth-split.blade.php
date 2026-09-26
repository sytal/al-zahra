@props(['hasTopbar' => false, 'maxContentWidth' => null])
@php
    use Filament\View\PanelsRenderHook;

    $livewire ??= null;
    $scopes = $livewire?->getRenderHookScopes();
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    <div class="az-auth">
        <aside class="az-auth-aside" aria-hidden="false">
            <img src="{{ asset('images/brand/logo-horizontal-dark.svg') }}" alt="{{ __('admin_ui.brand') }}" width="195" height="48" style="height:3rem;width:auto">
            <img class="az-illus" src="{{ asset('images/illustrations/illus-auth.svg') }}" alt="" width="480" height="400">
            <div>
                <p class="text-2xl font-semibold" style="font-family: 'Playfair Display', Georgia, serif;">{{ __('admin_ui.login_tagline') }}</p>
                <p class="mt-2 text-sm opacity-80">{{ __('admin_ui.login_sub') }}</p>
            </div>
        </aside>

        <div class="az-auth-main">
            <div class="az-auth-mobile-logo">
                <img src="{{ asset('images/brand/logo-horizontal.svg') }}" alt="{{ __('admin_ui.brand') }}" class="az-logo-light" width="163" height="40" style="height:2.5rem;width:auto">
                <img src="{{ asset('images/brand/logo-horizontal-dark.svg') }}" alt="{{ __('admin_ui.brand') }}" class="az-logo-dark" width="163" height="40" style="height:2.5rem;width:auto">
            </div>
            {{ \Filament\Support\Facades\FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START, scopes: $scopes) }}
            <div class="fi-simple-main-ctn">
                <main id="fi-main-content" tabindex="-1" class="fi-simple-main fi-width-md">
                    {{ $slot }}
                </main>
            </div>
            {{ \Filament\Support\Facades\FilamentView::renderHook(PanelsRenderHook::FOOTER, scopes: $scopes) }}
            {{ \Filament\Support\Facades\FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_END, scopes: $scopes) }}
        </div>
    </div>
</x-filament-panels::layout.base>
