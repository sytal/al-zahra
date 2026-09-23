@push('head')
    <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" :title-tag="false" />
@endpush

<div class="w-full max-w-sm">
    <x-card>
        <h1 class="mb-6 text-center text-xl font-semibold text-ink">{{ __('auth-pages.login_title') }}</h1>

        <form wire:submit="login" class="space-y-4">
            <x-input name="email" type="email" :label="__('auth-pages.login_email')" wire:model="email" autofocus autocomplete="username" />
            <x-input name="password" type="password" :label="__('auth-pages.login_password')" wire:model="password" autocomplete="current-password" />

            <div class="flex items-center justify-between">
                <x-checkbox name="remember" :label="__('auth-pages.login_remember')" wire:model="remember" />
                <a href="{{ route('password.request', app()->getLocale()) }}" wire:navigate class="text-sm text-brand-primary hover:underline">
                    {{ __('auth-pages.login_forgot_password') }}
                </a>
            </div>

            <x-button type="submit" variant="primary" class="w-full justify-center">
                {{ __('auth-pages.login_submit') }}
            </x-button>
        </form>

        <p class="mt-6 text-center text-sm text-ink/60">
            {{ __('auth-pages.login_no_account') }}
            <a href="{{ route('register', app()->getLocale()) }}" wire:navigate class="text-brand-primary hover:underline">
                {{ __('auth-pages.login_register_link') }}
            </a>
        </p>
    </x-card>
</div>
