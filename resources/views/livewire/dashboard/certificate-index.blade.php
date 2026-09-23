<div class="mx-auto max-w-6xl px-4 py-8" data-aos="fade-up">
    <h1 class="text-2xl font-bold text-ink">{{ __('dashboard.certificates_page_title') }}</h1>

    <div class="mt-6">
        @if ($certificates->isEmpty())
            <x-empty-state icon="document-check" :title="__('dashboard.certificates_empty_title')" :message="__('dashboard.certificates_empty_message')">
                <x-slot name="action">
                    <x-button :href="route('dashboard.courses.index', app()->getLocale())" variant="primary">
                        {{ __('dashboard.certificates_browse_courses_cta') }}
                    </x-button>
                </x-slot>
            </x-empty-state>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($certificates as $certificate)
                    <x-card hoverable>
                        <x-icon name="document-check" class="size-8 text-brand-primary" />
                        <h3 class="mt-3 font-medium text-ink">{{ $certificate->course?->title }}</h3>
                        <p class="mt-1 text-sm text-ink/60">
                            {{ __('dashboard.certificates_issued_on') }} {{ $certificate->issued_at?->format('M d, Y') }}
                        </p>
                        <p class="mt-1 text-xs text-ink/50">
                            {{ __('dashboard.certificates_verification_code') }}: {{ $certificate->verification_code }}
                        </p>
                        <div class="mt-4">
                            <a
                                href="{{ route('dashboard.certificates.download', ['locale' => app()->getLocale(), 'certificate' => $certificate->uuid]) }}"
                                class="inline-flex items-center justify-center gap-2 rounded-lg border border-brand-primary px-3 py-1.5 text-sm font-medium text-brand-primary transition duration-200 ease-in-out hover:bg-brand-primary/10"
                            >
                                <x-icon name="arrow-down-tray" class="size-4" />
                                {{ __('dashboard.certificates_download_cta') }}
                            </a>
                        </div>
                    </x-card>
                @endforeach
            </div>
        @endif
    </div>
</div>
