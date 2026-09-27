@php
$locale = app()->getLocale();
$user = auth()->user();
$verifyUrl = route('certificates.verify.form', $locale);
@endphp

<div class="mx-auto flex w-full max-w-6xl flex-col gap-6 px-4 py-6 sm:px-6 sm:py-8">
    <x-app.page-header :eyebrow="__('dashboard.nav_certificates')" :title="__('dashboard.certificates_page_title')" :description="__('dash_home_ui.certificates_lead')">
        <x-slot name="actions">
            @if ($certificates->isNotEmpty())
                <x-badge color="brand" variant="outline" icon="document-check" :text="__('dash_home_ui.cert_count', ['count' => $certificates->count()])" />
            @endif
            <x-button :href="$verifyUrl" variant="outline" icon="shield-check">{{ __('dash_home_ui.qa_verify') }}</x-button>
        </x-slot>
    </x-app.page-header>

    @if (session('error'))
        <x-alert variant="warning">{{ session('error') }}</x-alert>
    @endif

    @if ($certificates->isEmpty())
        <x-empty-state illustration="success" icon="document-check" :title="__('dashboard.certificates_empty_title')" :message="__('dash_home_ui.cert_empty_hint')">
            <x-slot name="action">
                <x-button :href="route('dashboard.courses.index', $locale)" variant="primary">{{ __('dashboard.certificates_browse_courses_cta') }}</x-button>
            </x-slot>
        </x-empty-state>
    @else
        <div class="grid gap-5 md:grid-cols-2">
            @foreach ($certificates as $certificate)
                <article wire:key="cert-{{ $certificate->uuid }}" class="card-surface card-hover flex min-w-0 flex-col overflow-hidden">
                    <div class="relative isolate flex aspect-[16/10] items-center justify-center overflow-hidden bg-tint p-5 text-center sm:p-6">
                        <img src="{{ asset('images/illustrations/certificate-border.svg') }}" alt="" width="800" height="500" loading="lazy" decoding="async" class="absolute inset-0 -z-10 size-full object-fill opacity-80">
                        <span class="star-mark pointer-events-none absolute text-[9rem] opacity-10" aria-hidden="true"></span>
                        <div class="relative max-w-[80%] space-y-1.5">
                            <p class="eyebrow text-secondary-text">{{ __('dash_home_ui.cert_completion') }}</p>
                            <h3 class="font-display text-lg font-semibold leading-snug text-strong break-words sm:text-xl">{{ $certificate->course?->title }}</h3>
                            <p class="text-xs text-body">{{ __('dash_home_ui.cert_awarded_to') }}</p>
                            <p class="font-display text-base font-semibold text-strong break-words">{{ $user->name }}</p>
                        </div>
                    </div>

                    <div class="flex flex-1 flex-col gap-4 p-5">
                        <dl class="grid gap-3 text-sm sm:grid-cols-2">
                            <div class="min-w-0">
                                <dt class="text-xs font-semibold text-secondary-text">{{ __('dashboard.certificates_issued_on') }}</dt>
                                <dd class="text-strong">{{ $certificate->issued_at?->translatedFormat('M d, Y') }}</dd>
                            </div>
                            <div class="min-w-0">
                                <dt class="text-xs font-semibold text-secondary-text">{{ __('dashboard.certificates_verification_code') }}</dt>
                                <dd class="break-all font-mono text-strong" dir="ltr">{{ $certificate->verification_code }}</dd>
                            </div>
                        </dl>

                        <div class="mt-auto flex flex-wrap gap-2">
                            <x-button :href="route('dashboard.certificates.download', ['locale' => $locale, 'certificate' => $certificate->uuid])" size="sm" icon="arrow-down-tray" :navigate="false">{{ __('dashboard.certificates_download_cta') }}</x-button>

                            <span x-data="copyToClipboard({ text: @js($certificate->verification_code) })" class="contents">
                                <button type="button" x-on:click="copy" class="inline-flex min-h-9 items-center justify-center gap-1.5 rounded-xl bg-brand/10 px-4 py-1.5 text-sm font-semibold text-link outline-none transition duration-fast focus-visible:ring-4 focus-visible:ring-brand/35 active:scale-[0.98] [@media(pointer:coarse)]:min-h-11 [@media(hover:hover)]:hover:bg-brand/15">
                                    <x-icon name="clipboard-document" class="size-4" x-show="!copied" aria-hidden="true" />
                                    <x-icon name="check" class="size-4" x-show="copied" x-cloak aria-hidden="true" />
                                    <span x-show="!copied">{{ __('dash_home_ui.cert_copy_code') }}</span>
                                    <span x-show="copied" x-cloak role="status">{{ __('dash_home_ui.cert_code_copied') }}</span>
                                </button>
                            </span>

                            <span x-data="copyToClipboard({ text: @js($verifyUrl) })" class="contents">
                                <button type="button" x-on:click="copy" class="inline-flex min-h-9 items-center justify-center gap-1.5 rounded-xl px-4 py-1.5 text-sm font-semibold text-strong outline-none transition duration-fast focus-visible:ring-4 focus-visible:ring-brand/35 active:scale-[0.98] [@media(pointer:coarse)]:min-h-11 [@media(hover:hover)]:hover:bg-surface-sunken">
                                    <x-icon name="share" class="size-4" x-show="!copied" aria-hidden="true" />
                                    <x-icon name="check" class="size-4" x-show="copied" x-cloak aria-hidden="true" />
                                    <span x-show="!copied">{{ __('dash_home_ui.cert_share') }}</span>
                                    <span x-show="copied" x-cloak role="status">{{ __('dash_home_ui.cert_share_copied') }}</span>
                                </button>
                            </span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
