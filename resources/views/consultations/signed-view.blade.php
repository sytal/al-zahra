<x-layouts.minimal>
    <x-slot:seo><title>{{ __('consultation.question') }} | {{ config('app.name') }}</title></x-slot:seo>
    <x-card class="w-full max-w-xl">
        <p class="text-sm text-ink/70">{{ __('consultation.question') }}</p>
        <p dir="auto" class="mt-1 whitespace-pre-line break-words text-ink">{{ $consultation->question }}</p>

        @if ($consultation->answer)
            <div class="mt-6 border-t border-ink/10 pt-6">
                <p class="text-sm text-ink/70">{{ __('consultation.mail_view_button') }}</p>
                <p dir="auto" class="mt-1 whitespace-pre-line break-words text-ink">{{ $consultation->answer }}</p>
                @if ($consultation->answered_at)
                    <p class="mt-2 text-xs text-ink/70">{{ $consultation->answered_at->translatedFormat('M d, Y') }}</p>
                @endif
            </div>
        @else
            <div class="mt-6 border-t border-ink/10 pt-6">
                <x-badge color="warning" :text="__('enums.consultation_status.'.$consultation->status->value)" />
            </div>
        @endif

        <div class="mt-8 text-center">
            <a href="{{ route('consultation.show', app()->getLocale()) }}" wire:navigate class="text-sm text-brand-primary hover:underline">
                {{ __('consultation.page_title') }} <span aria-hidden="true" class="inline-block rtl:-scale-x-100">&rarr;</span>
            </a>
        </div>
    </x-card>
</x-layouts.minimal>
