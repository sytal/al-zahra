<div>
    @if ($subscribed)
        <p role="status" class="text-sm text-ink">{{ __('newsletter.thanks') }}</p>
    @else
        <form wire:submit="subscribe" class="flex flex-wrap gap-2">
            <input
                type="email" name="email" autocomplete="email" aria-label="{{ __('newsletter.placeholder') }}" @error('email') aria-invalid="true" @enderror
                wire:model="email"
                placeholder="{{ __('newsletter.placeholder') }}"
                class="min-w-0 flex-1 rounded-lg border-ink/20 bg-white dark:bg-surface text-base sm:text-sm text-ink shadow-sm focus:border-brand-primary focus:ring-brand-primary"
            >
            <x-button type="submit" size="sm">{{ __('newsletter.subscribe') }}</x-button>
        </form>
        @error('email')<p role="alert" class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
    @endif
</div>
