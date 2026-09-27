@push('head')
    <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" :title-tag="false" />
@endpush

<div class="w-full max-w-md" x-data="{ show: false }" x-init="$nextTick(() => $refs.form.querySelector('input')?.focus({ preventScroll: true }))">
    <div class="gradient-border relative overflow-hidden rounded-panel bg-surface-raised p-6 shadow-lift sm:p-8">
        <div class="glow-gold -end-16 -top-16" style="--glow-size: 12rem" aria-hidden="true"></div>
        <header class="relative mb-6">
            <p class="eyebrow inline-flex items-center gap-2 text-secondary-text"><span class="star-mark text-sm" aria-hidden="true"></span>{{ __('auth_ui.login_welcome') }}</p>
            <h1 class="mt-2 font-display text-3xl font-bold text-strong">{{ __('auth-pages.login_title') }}</h1>
            <p class="mt-2 text-body">{{ __('auth_ui.login_sub') }}</p>
        </header>

        @if (session('status'))
            <x-alert variant="success" class="relative mb-4">{{ session('status') }}</x-alert>
        @endif

        <form x-ref="form" wire:submit="login" class="relative space-y-4" novalidate>
            <x-input name="email" type="email" :label="__('auth-pages.login_email')" wire:model="email" required autocomplete="username" />
            <x-input name="password" type="password" :label="__('auth-pages.login_password')" wire:model="password" required autocomplete="current-password" />

            <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-1">
                <x-checkbox name="remember" :label="__('auth-pages.login_remember')" wire:model="remember" />
                <a href="{{ route('password.request', app()->getLocale()) }}" wire:navigate class="link-underline tap-target inline-flex items-center text-sm font-medium text-link">{{ __('auth-pages.login_forgot_password') }}</a>
            </div>

            <x-button type="submit" variant="primary" size="lg" class="w-full">{{ __('auth-pages.login_submit') }}</x-button>
        </form>

        <p class="relative mt-6 border-t border-subtle pt-5 text-center text-sm text-body">
            {{ __('auth-pages.login_no_account') }}
            <a href="{{ route('register', app()->getLocale()) }}" wire:navigate class="link-underline font-semibold text-link">{{ __('auth-pages.login_register_link') }}</a>
        </p>
    </div>
</div>
