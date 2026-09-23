@push('head')
    <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" :title-tag="false" />
@endpush

<div class="w-full max-w-sm">
    <x-card>
        <h1 class="mb-2 text-center text-xl font-semibold text-ink">{{ __('auth-pages.confirm_password_title') }}</h1>
        <p class="mb-6 text-center text-sm text-ink/60">{{ __('auth-pages.confirm_password_intro') }}</p>

        <form wire:submit="confirm" class="space-y-4">
            <x-input name="password" type="password" :label="__('auth-pages.confirm_password_field')" wire:model="password" autofocus />

            <x-button type="submit" variant="primary" class="w-full justify-center">
                {{ __('auth-pages.confirm_password_submit') }}
            </x-button>
        </form>
    </x-card>
</div>
