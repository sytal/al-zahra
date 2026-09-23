<div>
    <x-slot name="seo">
        <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" />
        <meta name="robots" content="noindex, nofollow">
    </x-slot>

    <div class="mx-auto max-w-6xl px-4 py-8">
        <h1 class="text-2xl font-bold text-ink">{{ __('dashboard.my_courses_title') }}</h1>

        <div class="mt-6 flex gap-2 border-b border-ink/10">
            @foreach (['all' => __('dashboard.tab_all'), 'in_progress' => __('dashboard.tab_in_progress'), 'completed' => __('dashboard.tab_completed')] as $key => $label)
                <button
                    type="button"
                    wire:click="setTab('{{ $key }}')"
                    class="border-b-2 px-4 py-2 text-sm font-medium transition duration-200 ease-in-out {{ $tab === $key ? 'border-brand-primary text-brand-primary' : 'border-transparent text-ink/60 hover:text-ink' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>

        @if ($enrollments->isEmpty())
            @if (! $hasAnyEnrollment)
                <x-empty-state icon="academic-cap" :title="__('dashboard.no_enrollments_title')">
                    <x-slot name="action">
                        <x-button :href="route('courses.index', app()->getLocale())" variant="primary">{{ __('dashboard.browse_courses_cta') }}</x-button>
                    </x-slot>
                </x-empty-state>
            @else
                <x-empty-state icon="academic-cap" :title="__('dashboard.no_courses_in_progress')" />
            @endif
        @else
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($enrollments as $enrollment)
                    <x-card>
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-medium text-ink">{{ $enrollment->course->title }}</h3>

                            @if ($enrollment->status === \App\Support\Enums\EnrollmentStatus::COMPLETED)
                                <a
                                    href="{{ route('dashboard.certificates.index', app()->getLocale()) }}"
                                    wire:navigate
                                    title="{{ __('dashboard.view_certificate') }}"
                                    class="shrink-0 text-brand-secondary hover:text-brand-secondary/80"
                                >
                                    <x-icon name="document-check" class="size-5" />
                                </a>
                            @endif
                        </div>

                        <div class="mt-3">
                            <x-progress-bar :percent="$enrollment->progress_percent" />
                        </div>

                        <div class="mt-4">
                            <x-button
                                :href="route('dashboard.courses.learn', ['locale' => app()->getLocale(), 'course' => $enrollment->course->slug])"
                                variant="outline"
                                size="sm"
                            >
                                {{ $enrollment->status === \App\Support\Enums\EnrollmentStatus::COMPLETED ? __('dashboard.review_btn') : __('dashboard.continue_btn') }}
                            </x-button>
                        </div>
                    </x-card>
                @endforeach
            </div>
        @endif
    </div>
</div>
