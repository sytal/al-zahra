@push('head')
    <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" :title-tag="false" />
@endpush

<div class="w-full max-w-md">
    <x-card>
        <h1 class="mb-2 text-center text-xl font-semibold text-ink">{{ __('auth-pages.verify_email_title') }}</h1>
        <p class="mb-6 text-sm text-ink/60">{{ __('auth-pages.verify_email_intro') }}</p>

        @if ($resent)
            <p class="mb-4 text-sm text-success">{{ __('auth-pages.verify_email_resent') }}</p>
        @endif

        <div class="flex items-center justify-between">
            <x-button wire:click="resend" variant="primary">
                {{ __('auth-pages.verify_email_resend') }}
            </x-button>

            <button type="button" wire:click="logout" class="text-sm text-ink/60 hover:text-ink">
                {{ __('auth-pages.verify_email_logout') }}
            </button>
        </div>
    </x-card>
</div>
