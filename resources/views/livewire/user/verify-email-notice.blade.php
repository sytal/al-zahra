@push('head')
    <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" :title-tag="false" />
@endpush

<div class="w-full max-w-md">
    <x-card>
        <h1 class="mb-2 text-center text-xl font-semibold text-ink">{{ __('auth-pages.verify_email_title') }}</h1>
        <p class="mb-6 text-sm text-ink/70">{{ __('auth-pages.verify_email_intro') }}</p>

        @if ($resent)
            <p role="status" class="mb-4 rounded-lg border border-success/40 bg-success/10 p-3 text-sm text-ink">{{ __('auth-pages.verify_email_resent') }}</p>
        @endif

        <div class="flex flex-wrap items-center justify-between gap-3">
            <x-button wire:click="resend" variant="primary">
                {{ __('auth-pages.verify_email_resend') }}
            </x-button>

            <button type="button" wire:click="logout" class="inline-flex min-h-10 items-center px-2 text-sm text-ink/70 hover:text-ink">
                {{ __('auth-pages.verify_email_logout') }}
            </button>
        </div>
    </x-card>
</div>
