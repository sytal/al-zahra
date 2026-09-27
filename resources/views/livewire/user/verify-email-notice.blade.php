@push('head')
    <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" :title-tag="false" />
@endpush

<div class="w-full max-w-lg"
    x-data="{ left: 0, timer: null, start() { this.left = 60; clearInterval(this.timer); this.timer = setInterval(() => { if (--this.left <= 0) clearInterval(this.timer) }, 1000) } }"
    @if ($resent) x-init="start()" @endif>
    <div class="gradient-border relative overflow-hidden rounded-panel bg-surface-raised p-6 text-center shadow-lift sm:p-8">
        <div class="glow-gold -end-16 -top-16" style="--glow-size: 12rem" aria-hidden="true"></div>
        <img src="{{ asset('images/illustrations/illus-auth.svg') }}" width="400" height="300" alt="" aria-hidden="true" class="relative mx-auto mb-4 h-32 w-auto max-w-full sm:h-40" loading="eager" decoding="async">
        <h1 class="relative font-display text-3xl font-bold text-strong">{{ __('auth_ui.verify_heading') }}</h1>
        <p class="relative mt-2 text-body">{{ __('auth_ui.verify_sent_to') }}</p>
        <p class="relative mt-1 break-all font-semibold text-strong" dir="ltr">{{ auth()->user()?->email }}</p>
        <p class="relative mt-4 text-sm text-body">{{ __('auth-pages.verify_email_intro') }}</p>

        @if ($resent)
            <x-alert variant="success" class="relative mt-5 text-start">{{ __('auth-pages.verify_email_resent') }}</x-alert>
        @endif

        <div class="relative mt-6 flex flex-col items-center gap-3">
            <x-button wire:click="resend" x-on:click="if (left <= 0) start()" x-bind:disabled="left > 0" variant="primary" size="lg" class="w-full sm:w-auto">
                {{ __('auth-pages.verify_email_resend') }}
            </x-button>
            <p class="text-sm text-muted" x-show="left > 0" x-cloak aria-live="polite" x-text="@js(__('auth_ui.verify_resend_in')).replace(':seconds', left)"></p>
            <p class="text-xs text-muted">{{ __('auth_ui.verify_spam') }}</p>
        </div>

        <div class="relative mt-6 border-t border-subtle pt-4">
            <button type="button" wire:click="logout" class="link-underline tap-target inline-flex items-center gap-2 text-sm font-medium text-body [@media(hover:hover)]:hover:text-strong">
                <x-icon name="arrow-right-on-rectangle" class="size-4" />{{ __('auth-pages.verify_email_logout') }}
            </button>
        </div>
    </div>
</div>
