<x-layouts.minimal>
    <x-card class="w-full max-w-xl">
        <p class="text-sm text-ink/60">{{ __('consultation.question') }}</p>
        <p class="mt-1 text-ink">{{ $consultation->question }}</p>

        @if ($consultation->answer)
            <div class="mt-6 border-t border-ink/10 pt-6">
                <p class="text-sm text-ink/60">{{ __('consultation.mail_view_button') }}</p>
                <p class="mt-1 text-ink">{{ $consultation->answer }}</p>
                @if ($consultation->answered_at)
                    <p class="mt-2 text-xs text-ink/40">{{ $consultation->answered_at->format('M d, Y') }}</p>
                @endif
            </div>
        @else
            <div class="mt-6 border-t border-ink/10 pt-6">
                <x-badge color="warning" :text="ucfirst($consultation->status->value)" />
            </div>
        @endif

        <div class="mt-8 text-center">
            <a href="{{ route('consultation.show', app()->getLocale()) }}" wire:navigate class="text-sm text-brand-primary hover:underline">
                {{ __('consultation.page_title') }} &rarr;
            </a>
        </div>
    </x-card>
</x-layouts.minimal>
