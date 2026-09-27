@push('head')
    <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" :title-tag="false" />
@endpush

<div class="w-full max-w-md" x-data="{ show: false }" x-init="$nextTick(() => $refs.form.querySelector('input')?.focus({ preventScroll: true }))">
    <div class="gradient-border relative overflow-hidden rounded-panel bg-surface-raised p-6 shadow-lift sm:p-8">
        <div class="glow-gold -end-16 -top-16" style="--glow-size: 12rem" aria-hidden="true"></div>
        <header class="relative mb-6 text-center">
            <span class="mx-auto mb-4 flex size-14 items-center justify-center rounded-2xl bg-primary text-on-brand shadow-glow"><x-icon name="lock-closed" class="size-7" /></span>
            <p class="eyebrow text-secondary-text">{{ __('auth_ui.confirm_secure') }}</p>
            <h1 class="mt-1 font-display text-3xl font-bold text-strong">{{ __('auth-pages.confirm_password_title') }}</h1>
            <p class="mt-2 text-body">{{ __('auth-pages.confirm_password_intro') }}</p>
        </header>

        <form x-ref="form" wire:submit="confirm" class="relative space-y-4" novalidate>
            <x-input name="password" type="password" :label="__('auth-pages.confirm_password_field')" wire:model="password" required autocomplete="current-password" />
            <x-button type="submit" variant="primary" size="lg" class="w-full">{{ __('auth-pages.confirm_password_submit') }}</x-button>
        </form>
    </div>
</div>
