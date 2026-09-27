@push('head')
    <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" :title-tag="false" />
@endpush

<div class="w-full max-w-md"
    x-data="{
        show: false, pw: '', pc: '',
        labels: @js([__('auth_ui.strength_empty'), __('auth_ui.strength_weak'), __('auth_ui.strength_fair'), __('auth_ui.strength_good'), __('auth_ui.strength_strong')]),
        get checks() { return { length: this.pw.length >= 8, case: /[a-z]/.test(this.pw) && /[A-Z]/.test(this.pw), number: /\d/.test(this.pw), symbol: /[^A-Za-z0-9]/.test(this.pw) } },
        get score() { return this.pw ? Object.values(this.checks).filter(Boolean).length : 0 },
        get match() { return this.pw !== '' && this.pw === this.pc },
    }"
    x-init="$nextTick(() => $refs.form.querySelector('input')?.focus({ preventScroll: true }))">
    <div class="gradient-border relative overflow-hidden rounded-panel bg-surface-raised p-6 shadow-lift sm:p-8">
        <div class="glow-teal -start-16 -top-16" style="--glow-size: 12rem" aria-hidden="true"></div>
        <header class="relative mb-6">
            <p class="eyebrow inline-flex items-center gap-2 text-secondary-text"><span class="star-mark text-sm" aria-hidden="true"></span>{{ __('auth_ui.register_welcome') }}</p>
            <h1 class="mt-2 font-display text-3xl font-bold text-strong">{{ __('auth-pages.register_title') }}</h1>
            <p class="mt-2 text-body">{{ __('auth_ui.register_sub') }}</p>
        </header>

        <form x-ref="form" wire:submit="register" class="relative space-y-4" novalidate
            x-on:input="if ($event.target.name === 'password') pw = $event.target.value; if ($event.target.name === 'password_confirmation') pc = $event.target.value">
            <x-input name="name" :label="__('auth-pages.register_name')" wire:model="name" required autocomplete="name" />
            <x-input name="email" type="email" :label="__('auth-pages.register_email')" wire:model="email" required autocomplete="username" />
            <x-input name="password" type="password" :label="__('auth-pages.register_password')" wire:model="password" required autocomplete="new-password" />

            <div class="space-y-3 rounded-2xl bg-tint p-4" aria-live="polite">
                <div class="flex items-center justify-between gap-3 text-sm">
                    <span class="font-medium text-strong">{{ __('auth_ui.strength') }}</span>
                    <span class="font-semibold text-strong" x-text="labels[score]">{{ __('auth_ui.strength_empty') }}</span>
                </div>
                <div class="flex gap-1.5" aria-hidden="true">
                    <template x-for="i in 4" :key="i">
                        <span class="h-1.5 flex-1 rounded-full transition-colors duration-base"
                            :class="i <= score ? (score <= 1 ? 'bg-danger' : (score === 2 ? 'bg-warning' : 'bg-success')) : 'bg-subtle/40'"></span>
                    </template>
                </div>
                <p class="text-xs font-medium text-body">{{ __('auth_ui.req_title') }}</p>
                <ul class="grid gap-1.5 text-sm text-body sm:grid-cols-2">
                    @foreach (['length', 'case', 'number', 'symbol'] as $req)
                        <li class="flex items-center gap-2 transition-colors duration-fast" :class="checks.{{ $req }} ? 'text-strong' : ''">
                            <x-icon name="check-circle" class="size-5 shrink-0 transition-colors duration-fast" x-bind:class="checks.{{ $req }} ? 'text-success' : 'text-subtle'" />
                            <span class="min-w-0 break-words">{{ __('auth_ui.req_'.$req) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <x-input name="password_confirmation" type="password" :label="__('auth-pages.register_password_confirmation')" wire:model="password_confirmation" required autocomplete="new-password" />
            <p class="flex items-center gap-2 text-sm text-body" x-show="pc !== ''" x-cloak :class="match ? 'text-strong' : ''">
                <x-icon name="check-circle" class="size-5 shrink-0" x-bind:class="match ? 'text-success' : 'text-subtle'" />
                {{ __('auth_ui.req_match') }}
            </p>

            <x-button type="submit" variant="primary" size="lg" class="w-full">{{ __('auth-pages.register_submit') }}</x-button>
            <p class="text-center text-xs text-muted">{{ __('auth_ui.terms_note') }}</p>
        </form>

        <p class="relative mt-6 border-t border-subtle pt-5 text-center text-sm text-body">
            {{ __('auth-pages.register_have_account') }}
            <a href="{{ route('login', app()->getLocale()) }}" wire:navigate class="link-underline font-semibold text-link">{{ __('auth-pages.register_login_link') }}</a>
        </p>
    </div>
</div>
