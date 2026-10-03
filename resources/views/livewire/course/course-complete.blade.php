<div>
    <x-slot name="seo">
        <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" />
        <meta name="robots" content="noindex, nofollow">
    </x-slot>

    @php $locale = app()->getLocale(); @endphp

    <div class="mx-auto flex max-w-2xl flex-col items-center gap-6 px-4 py-16 text-center">
        <span class="flex size-16 items-center justify-center rounded-full bg-tint text-brand-primary">
            <x-icon name="check-circle" class="size-10 text-success" aria-hidden="true" />
        </span>

        <h1 class="heading-2 text-strong">{{ __('course_learn_ui.complete_title') }}</h1>
        <p class="text-body">{{ __('course_learn_ui.complete_body') }}</p>

        <p class="text-lg font-semibold text-strong">
            @if ($enrollment->final_score !== null)
                {{ __('course_learn_ui.complete_score', ['score' => (int) round($enrollment->final_score)]) }}
            @else
                {{ __('course_learn_ui.complete_score_none') }}
            @endif
        </p>

        <div class="flex flex-wrap items-center justify-center gap-3">
            @if ($certificate && $certificate->hasMedia('certificate_pdf'))
                <x-button href="{{ route('dashboard.certificates.download', ['locale' => $locale, 'certificate' => $certificate->id]) }}" variant="primary" icon="arrow-down-tray">
                    {{ __('course_learn_ui.complete_download_certificate') }}
                </x-button>
            @elseif ($certificate)
                <p class="text-sm text-muted">{{ __('course_learn_ui.complete_certificate_preparing') }}</p>
            @endif

            <x-button href="{{ route('dashboard.courses.index', ['locale' => $locale]) }}" variant="secondary">
                {{ __('course_learn_ui.complete_back_to_courses') }}
            </x-button>
        </div>
    </div>
</div>
