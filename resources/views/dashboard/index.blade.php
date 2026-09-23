<x-layouts.dashboard>
    <div class="mx-auto max-w-6xl px-4 py-8">
        <h1 class="text-2xl font-bold text-ink">{{ __('dashboard.welcome_back', ['name' => auth()->user()->name]) }}</h1>

        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <x-stat-card icon="academic-cap" :label="__('dashboard.stat_courses_in_progress')" :value="$stats['courses_in_progress']" />
            <x-stat-card icon="document-check" :label="__('dashboard.stat_certificates_earned')" :value="$stats['certificates_earned']" />
            <x-stat-card icon="chat-bubble-left-right" :label="__('dashboard.stat_consultations')" :value="$stats['consultations']" />
        </div>

        <div class="mt-8">
            <h2 class="text-lg font-semibold text-ink">{{ __('dashboard.continue_learning') }}</h2>

            @if ($inProgress->isEmpty())
                <x-empty-state icon="academic-cap" :title="__('dashboard.no_courses_in_progress')">
                    <x-slot name="action">
                        <x-button :href="route('courses.index', app()->getLocale())" variant="primary">{{ __('dashboard.browse_courses_cta') }}</x-button>
                    </x-slot>
                </x-empty-state>
            @else
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    @foreach ($inProgress as $enrollment)
                        <x-card>
                            <a href="{{ route('dashboard.courses.learn', ['locale' => app()->getLocale(), 'course' => $enrollment->course->slug]) }}" wire:navigate>
                                <h3 class="font-medium text-ink">{{ $enrollment->course->title }}</h3>
                            </a>
                            <div class="mt-3">
                                <x-progress-bar :percent="$enrollment->progress_percent" />
                            </div>
                        </x-card>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mt-8">
            <h2 class="text-lg font-semibold text-ink">{{ __('dashboard.recent_consultations') }}</h2>

            @if ($recentConsultations->isEmpty())
                <x-empty-state icon="chat-bubble-left-right" :title="__('dashboard.no_consultations_yet')">
                    <x-slot name="action">
                        <x-button :href="route('consultation.show', app()->getLocale())" variant="primary">{{ __('dashboard.ask_question_cta') }}</x-button>
                    </x-slot>
                </x-empty-state>
            @else
                <div class="mt-4 space-y-3">
                    @foreach ($recentConsultations as $consultation)
                        <x-card class="flex items-center justify-between">
                            <span class="text-ink">{{ $consultation->topic ?: __('consultation.mail_no_topic') }}</span>
                            <x-badge color="neutral" :text="ucfirst($consultation->status->value)" />
                        </x-card>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.dashboard>
