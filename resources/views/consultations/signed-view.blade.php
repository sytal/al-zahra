<x-layouts.minimal>
    <x-slot:seo><title>{{ __('auth_ui.signed_title') }} | {{ config('app.name') }}</title></x-slot:seo>
    <div class="w-full max-w-2xl">
        <header class="mb-6 text-center">
            <p class="eyebrow inline-flex items-center gap-2 text-secondary-text"><span class="star-mark text-sm" aria-hidden="true"></span>{{ __('consultation.page_title') }}</p>
            <h1 class="mt-2 font-display text-3xl font-bold text-strong sm:text-4xl">{{ __('auth_ui.signed_title') }}</h1>
        </header>

        <div class="gradient-border relative overflow-hidden rounded-panel bg-surface-raised p-4 shadow-lift sm:p-8">
            <div class="glow-teal -start-16 -top-16" style="--glow-size: 12rem" aria-hidden="true"></div>
            <div class="relative space-y-6">
                <div class="flex flex-col items-end gap-1.5" data-question>
                    <p class="text-xs font-medium text-muted">{{ __('auth_ui.signed_you') }}@if ($consultation->created_at) &middot; {{ $consultation->created_at->translatedFormat('j M Y') }}@endif</p>
                    <div dir="auto" class="max-w-[92%] whitespace-pre-line break-words rounded-2xl rounded-ee-md bg-primary px-4 py-3 text-on-brand shadow-soft sm:max-w-[85%]">{{ $consultation->question }}</div>
                </div>

                @if ($consultation->answer)
                    <div class="flex items-end gap-2.5">
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-tint text-secondary-text" aria-hidden="true"><span class="star-mark text-base"></span></span>
                        <div class="flex min-w-0 max-w-[92%] flex-col items-start gap-1.5 sm:max-w-[85%]">
                            <p class="text-xs font-medium text-muted">{{ __('auth_ui.signed_answer_from') }}@if ($consultation->answered_at) &middot; {{ $consultation->answered_at->translatedFormat('j M Y') }}@endif</p>
                            <div dir="auto" class="whitespace-pre-line break-words rounded-2xl rounded-es-md border border-subtle bg-surface-sunken px-4 py-3 text-strong">{{ $consultation->answer }}</div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3 rounded-2xl bg-tint p-4" role="status">
                        <span class="star-loader shrink-0" aria-hidden="true"></span>
                        <div class="min-w-0">
                            <x-badge color="warning" :text="__('enums.consultation_status.'.$consultation->status->value)" />
                            <p class="mt-1.5 text-sm text-body">{{ __('auth_ui.signed_pending') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-section-dark relative mt-6 overflow-hidden rounded-panel p-6 text-center sm:p-8">
            <div class="glow-gold -bottom-16 -end-16" style="--glow-size: 14rem" aria-hidden="true"></div>
            <h2 class="relative font-display text-2xl font-bold text-strong">{{ __('auth_ui.signed_another') }}</h2>
            <p class="relative mt-1 text-body">{{ __('auth_ui.signed_another_text') }}</p>
            <div class="relative mt-5 flex justify-center">
                <x-button :href="route('consultation.show', app()->getLocale())" navigate variant="secondary" size="lg" icon-end="arrow-right">{{ __('auth_ui.signed_cta') }}</x-button>
            </div>
        </div>
    </div>
</x-layouts.minimal>
