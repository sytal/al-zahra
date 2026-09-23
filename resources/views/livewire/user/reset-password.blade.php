@push('head')
    <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" :title-tag="false" />
@endpush

<div class="w-full max-w-sm">
    <x-card>
        <h1 class="mb-6 text-center text-xl font-semibold text-ink">{{ __('auth-pages.reset_password_title') }}</h1>

        <form wire:submit="resetPassword" class="space-y-4">
            <x-input name="email" type="email" :label="__('auth-pages.reset_password_email')" wire:model="email" autofocus />
            <x-input name="password" type="password" :label="__('auth-pages.reset_password_new')" wire:model="password" />
            <x-input name="password_confirmation" type="password" :label="__('auth-pages.reset_password_confirm')" wire:model="password_confirmation" />

            <x-button type="submit" variant="primary" class="w-full justify-center">
                {{ __('auth-pages.reset_password_submit') }}
            </x-button>
        </form>
    </x-card>
</div>
