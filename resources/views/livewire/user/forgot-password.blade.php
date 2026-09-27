@push('head')
    <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" :title-tag="false" />
@endpush

<div class="w-full max-w-md" x-init="$nextTick(() => $refs.form.querySelector('input')?.focus({ preventScroll: true }))">
    <div class="gradient-border relative overflow-hidden rounded-panel bg-surface-raised p-6 shadow-lift sm:p-8">
        <div class="glow-gold -end-16 -top-16" style="--glow-size: 12rem" aria-hidden="true"></div>
        @php $current = $status ? 2 : 1; @endphp
        <ol class="relative mb-6 flex items-start gap-2 text-xs font-medium text-body" aria-label="{{ __('auth-pages.forgot_password_title') }}">
            @foreach ([1, 2, 3] as $n)
                <li class="flex min-w-0 flex-1 flex-col gap-1.5" @if ($current === $n) aria-current="step" @endif>
                    <span class="h-1.5 rounded-full {{ $current >= $n ? 'bg-brand' : 'bg-subtle/40' }}"></span>
                    <span class="break-words {{ $current === $n ? 'font-semibold text-strong' : '' }}">{{ __('auth_ui.forgot_step_'.$n) }}</span>
                </li>
            @endforeach
        </ol>

        <header class="relative mb-6">
            <h1 class="font-display text-3xl font-bold text-strong">{{ __('auth-pages.forgot_password_title') }}</h1>
            <p class="mt-2 text-body">{{ __('auth-pages.forgot_password_intro') }}</p>
        </header>

        @if ($status)
            <div class="relative mb-5 text-center" role="status">
                <span class="star-burst mx-auto mb-3 flex size-16 items-center justify-center rounded-full bg-success/15 text-success"><x-icon name="envelope" class="size-8" /></span>
                <p class="font-semibold text-strong">{{ __('auth_ui.forgot_sent_title') }}</p>
                <p class="mt-1 text-sm text-body">{{ __('auth_ui.forgot_sent_text') }}</p>
                <x-alert variant="success" class="mt-4 text-start">{{ $status }}</x-alert>
            </div>
        @endif

        <form x-ref="form" wire:submit="sendResetLink" class="relative space-y-4" novalidate>
            <x-input name="email" type="email" :label="__('auth-pages.forgot_password_email')" wire:model="email" required autocomplete="email" />
            <x-button type="submit" variant="primary" size="lg" class="w-full">{{ __('auth-pages.forgot_password_submit') }}</x-button>
        </form>

        <p class="relative mt-6 border-t border-subtle pt-5 text-center text-sm">
            <a href="{{ route('login', app()->getLocale()) }}" wire:navigate class="link-underline inline-flex items-center gap-2 font-semibold text-link">
                <x-icon name="arrow-left" class="size-4 rtl:-scale-x-100" />{{ __('auth-pages.forgot_password_back_to_login') }}
            </a>
        </p>
    </div>
</div>
