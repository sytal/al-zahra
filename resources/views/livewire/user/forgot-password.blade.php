@push('head')
    <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" :title-tag="false" />
@endpush

<div class="w-full max-w-sm">
    <x-card>
        <h1 class="mb-2 text-center text-xl font-semibold text-ink">{{ __('auth-pages.forgot_password_title') }}</h1>
        <p class="mb-6 text-center text-sm text-ink/60">{{ __('auth-pages.forgot_password_intro') }}</p>

        @if ($status)
            <p class="mb-4 text-center text-sm text-success">{{ $status }}</p>
        @endif

        <form wire:submit="sendResetLink" class="space-y-4">
            <x-input name="email" type="email" :label="__('auth-pages.forgot_password_email')" wire:model="email" autofocus />

            <x-button type="submit" variant="primary" class="w-full justify-center">
                {{ __('auth-pages.forgot_password_submit') }}
            </x-button>
        </form>

        <p class="mt-6 text-center text-sm">
            <a href="{{ route('login', app()->getLocale()) }}" wire:navigate class="text-brand-primary hover:underline">
                {{ __('auth-pages.forgot_password_back_to_login') }}
            </a>
        </p>
    </x-card>
</div>
