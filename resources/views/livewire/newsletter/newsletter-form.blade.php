<div>
    @if ($subscribed)
        <p class="text-sm text-success">{{ __('newsletter.thanks') }}</p>
    @else
        <form wire:submit="subscribe" class="flex gap-2">
            <input
                type="email"
                wire:model="email"
                placeholder="{{ __('newsletter.placeholder') }}"
                class="rounded-lg border-ink/20 bg-white dark:bg-surface text-sm text-ink shadow-sm focus:border-brand-primary focus:ring-brand-primary"
            >
            <x-button type="submit" size="sm">{{ __('newsletter.subscribe') }}</x-button>
        </form>
        @error('email')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
    @endif
</div>
