@push('head')
    <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" :title-tag="false" />
@endpush

@php
$displayName = '';
if ($checked && $certificate) {
    $nameParts = preg_split('/\s+/', trim((string) $certificate->user?->name));
    $displayName = $nameParts[0] ?? '';
    if (count($nameParts) > 1) {
        $displayName .= ' '.mb_substr($nameParts[count($nameParts) - 1], 0, 1).'.';
    }
}
@endphp

<div class="mx-auto w-full max-w-xl min-w-0">
    <header class="mb-6 text-center">
        <p class="eyebrow mb-2 inline-flex items-center gap-2"><span class="star-mark text-sm" aria-hidden="true"></span>{{ __('forms_ui.verify_eyebrow') }}</p>
        <h1 class="heading-2 break-words">{{ __('certificates.page_title') }}</h1>
        <p class="mx-auto mt-3 max-w-md text-body">{{ __('certificates.page_intro') }}</p>
    </header>

    <div class="card-surface relative overflow-hidden p-5 shadow-lift sm:p-7" x-data>
        <form wire:submit="verify" class="space-y-4" novalidate
              x-on:submit="$nextTick(() => setTimeout(() => $el.querySelector('[aria-invalid=&quot;true&quot;]')?.focus(), 250))">
            <x-input
                name="code"
                icon="key"
                :label="__('certificates.code_label')"
                :hint="__('forms_ui.code_format_hint')"
                wire:model="code"
                autocomplete="off"
                autocapitalize="characters"
                spellcheck="false"
                maxlength="100"
                dir="ltr"
                enterkeyhint="go"
                placeholder="{{ __('certificates.code_placeholder') }}"
                required
            >
                <x-slot:suffix>
                    <button type="button" class="tap-target me-1 shrink-0 rounded-lg px-2 text-sm font-semibold text-link outline-none focus-visible:ring-2 focus-visible:ring-brand active:scale-95"
                            x-on:click="navigator.clipboard?.readText().then(t => { $wire.set('code', t.trim()) }).catch(() => {})">
                        {{ __('forms_ui.paste') }}
                    </button>
                </x-slot:suffix>
            </x-input>

            <x-button type="submit" variant="primary" size="lg" block icon="shield-check" wire:loading.attr="disabled" wire:target="verify">
                <span wire:loading.remove wire:target="verify">{{ __('certificates.submit') }}</span>
                <span wire:loading wire:target="verify">{{ __('forms_ui.verifying') }}</span>
            </x-button>
        </form>
    </div>

    <div class="mt-6" wire:loading.class="opacity-50" wire:target="verify" aria-live="polite">
        @if ($checked && $certificate)
            <div class="gradient-border-gold relative overflow-hidden rounded-panel bg-surface-raised p-6 text-center shadow-float sm:p-8" role="status" x-data="starBurst" x-init="$nextTick(() => fire())">
                <span class="glow-gold -top-24 start-1/2 -translate-x-1/2 opacity-50 rtl:translate-x-1/2" style="--glow-size: 20rem" aria-hidden="true"></span>
                <span class="bg-pattern-islamic pointer-events-none absolute inset-0 -z-10" aria-hidden="true"></span>

                <div class="star-burst relative mx-auto mb-4 flex size-20 items-center justify-center" :class="{ 'is-bursting': bursting }">
                    <span class="star-mark absolute inset-0 text-[5rem] opacity-90" aria-hidden="true"></span>
                    <x-icon name="check" class="relative size-8 text-on-secondary" aria-hidden="true" />
                </div>

                <p class="eyebrow text-success">{{ __('certificates.valid_title') }}</p>
                <h2 class="heading-2 mt-2 break-words">{{ $displayName }}</h2>
                <p class="mt-1 text-sm text-muted">{{ __('forms_ui.seal_line') }}</p>
                <p class="mt-3 font-display text-xl font-semibold break-words text-strong">{{ $certificate->course?->title }}</p>
                <x-star-divider class="my-5" />
                <dl class="grid gap-3 text-sm sm:grid-cols-2">
                    <div class="min-w-0 rounded-xl bg-tint p-3">
                        <dt class="text-muted">{{ __('certificates.issued_on') }}</dt>
                        <dd class="mt-0.5 font-semibold text-strong">{{ $certificate->issued_at?->translatedFormat('j F Y') }}</dd>
                    </div>
                    <div class="min-w-0 rounded-xl bg-tint p-3">
                        <dt class="text-muted">{{ __('certificates.code_label') }}</dt>
                        <dd class="mt-0.5 break-anywhere font-semibold text-strong" dir="ltr">{{ $certificate->verification_code }}</dd>
                    </div>
                </dl>
                <p class="mt-5 text-xs text-muted">{{ config('app.name') }}</p>
            </div>
        @elseif ($checked)
            <div class="card-surface p-6 text-center sm:p-8" role="status">
                <img src="{{ asset('images/illustrations/illus-empty.svg') }}" alt="" width="400" height="300" loading="lazy" decoding="async" class="mx-auto mb-3 h-auto w-full max-w-40">
                <h2 class="heading-3 break-words">{{ __('certificates.invalid_title') }}</h2>
                <p class="mx-auto mt-2 max-w-sm text-body">{{ __('certificates.invalid_message') }}</p>
                <p class="mx-auto mt-2 max-w-sm text-sm text-muted">{{ __('forms_ui.invalid_hint') }}</p>
            </div>
        @else
            <p class="flex items-center justify-center gap-2 text-center text-sm text-muted"><x-icon name="lock-closed" class="size-4 shrink-0" aria-hidden="true" />{{ __('forms_ui.verify_privacy') }}</p>
        @endif
    </div>
</div>
