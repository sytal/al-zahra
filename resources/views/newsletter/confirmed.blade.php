<x-layouts.minimal>
    <x-card class="w-full max-w-xl text-center">
        <x-icon name="check-circle" class="mx-auto size-10 text-success" />
        <h1 class="mt-3 text-xl font-semibold text-ink">{{ __('newsletter.confirm_title') }}</h1>
        <p class="mt-1 text-ink/60">{{ __('newsletter.confirm_message') }}</p>

        <div class="mt-8">
            <a href="{{ route('home', app()->getLocale()) }}" wire:navigate class="text-sm text-brand-primary hover:underline">
                {{ __('newsletter.confirm_home_link') }} &rarr;
            </a>
        </div>
    </x-card>
</x-layouts.minimal>
