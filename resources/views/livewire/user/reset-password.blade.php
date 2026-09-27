@push('head')
    <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" :title-tag="false" />
@endpush

<div class="w-full max-w-md" x-data="{ show: false }" x-init="$nextTick(() => $refs.form.querySelector('input[name=password]')?.focus({ preventScroll: true }))">
    <div class="gradient-border relative overflow-hidden rounded-panel bg-surface-raised p-6 shadow-lift sm:p-8">
        <div class="glow-teal -start-16 -top-16" style="--glow-size: 12rem" aria-hidden="true"></div>
        <header class="relative mb-6">
            <p class="eyebrow inline-flex items-center gap-2 text-secondary-text"><span class="star-mark text-sm" aria-hidden="true"></span>{{ __('auth_ui.forgot_step_3') }}</p>
            <h1 class="mt-2 font-display text-3xl font-bold text-strong">{{ __('auth-pages.reset_password_title') }}</h1>
            <p class="mt-2 text-body">{{ __('auth_ui.reset_intro') }}</p>
        </header>

        <form x-ref="form" wire:submit="resetPassword" class="relative space-y-4" novalidate>
            <x-input name="email" type="email" :label="__('auth-pages.reset_password_email')" wire:model="email" required autocomplete="username" />
            <x-input name="password" type="password" :label="__('auth-pages.reset_password_new')" wire:model="password" required autocomplete="new-password" />
            <x-input name="password_confirmation" type="password" :label="__('auth-pages.reset_password_confirm')" wire:model="password_confirmation" required autocomplete="new-password" />
            <x-button type="submit" variant="primary" size="lg" class="w-full">{{ __('auth-pages.reset_password_submit') }}</x-button>
        </form>
    </div>
</div>
