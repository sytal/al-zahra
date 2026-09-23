@push('head')
    <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" :title-tag="false" />
@endpush

<div class="w-full max-w-sm">
    <x-card>
        <h1 class="mb-6 text-center text-xl font-semibold text-ink">{{ __('auth-pages.register_title') }}</h1>

        <form wire:submit="register" class="space-y-4">
            <x-input name="name" :label="__('auth-pages.register_name')" wire:model="name" autofocus autocomplete="name" />
            <x-input name="email" type="email" :label="__('auth-pages.register_email')" wire:model="email" autocomplete="username" />
            <x-input name="password" type="password" :label="__('auth-pages.register_password')" wire:model="password" autocomplete="new-password" />
            <x-input name="password_confirmation" type="password" :label="__('auth-pages.register_password_confirmation')" wire:model="password_confirmation" autocomplete="new-password" />

            <x-button type="submit" variant="primary" class="w-full justify-center">
                {{ __('auth-pages.register_submit') }}
            </x-button>
        </form>

        <p class="mt-6 text-center text-sm text-ink/60">
            {{ __('auth-pages.register_have_account') }}
            <a href="{{ route('login', app()->getLocale()) }}" wire:navigate class="text-brand-primary hover:underline">
                {{ __('auth-pages.register_login_link') }}
            </a>
        </p>
    </x-card>
</div>
