@php
$locale = app()->getLocale();
$user = auth()->user();
$avatar = $user->getFirstMediaUrl('avatar') ?: null;
$statusColors = ['pending' => 'warning', 'answered' => 'success', 'scheduled' => 'brand', 'completed' => 'success', 'cancelled' => 'danger'];
$hasCourses = $inProgress->isNotEmpty();
@endphp

<x-layouts.dashboard>
    <div class="mx-auto flex w-full max-w-6xl flex-col gap-6 px-4 py-6 sm:px-6 sm:py-8 lg:gap-8">

        <section class="relative isolate overflow-hidden rounded-panel bg-section-dark p-6 shadow-float sm:p-8 lg:p-10" aria-labelledby="dash-greeting">
            <span class="glow-teal" style="--glow-size: 26rem; inset-inline-start: -6rem; top: -8rem" aria-hidden="true"></span>
            <span class="glow-gold" style="--glow-size: 18rem; inset-inline-end: -4rem; bottom: -8rem" aria-hidden="true"></span>
            <span class="bg-pattern-islamic absolute inset-0 -z-10 opacity-40" aria-hidden="true"></span>
            <span class="star-mark pointer-events-none absolute -end-6 -top-6 text-[9rem] opacity-15 sm:text-[12rem]" aria-hidden="true"></span>

            <div class="flex flex-col gap-6 lg:flex-row lg:items-center">
                <div class="flex items-center gap-4 lg:flex-1 lg:min-w-0">
                    <x-avatar :src="$avatar" :name="$user->name" size="xl" ringed class="shrink-0" />
                    <div class="min-w-0 flex-1 space-y-2">
                        <p class="eyebrow inline-flex items-center gap-2 text-secondary-text"><span class="star-mark text-sm" aria-hidden="true"></span>{{ __('dashboard.nav_dashboard') }}</p>
                        <h1 id="dash-greeting" class="heading-2 break-words leading-snug text-strong">{{ __('dash_home_ui.greet_'.$greetingKey, ['name' => $user->name]) }}</h1>
                        <p class="max-w-xl text-body">{{ $hasCourses ? __('dash_home_ui.home_lead_learning') : __('dash_home_ui.home_lead_new') }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3 lg:shrink-0 lg:flex-col">
                    <x-button :href="route('consultation.show', $locale)" variant="secondary" icon="chat-bubble-left-right" magnetic>{{ __('dash_home_ui.qa_ask') }}</x-button>
                    <x-button :href="route('dashboard.courses.index', $locale)" variant="outline" icon="academic-cap">{{ __('dash_home_ui.qa_courses') }}</x-button>
                </div>
            </div>
        </section>

        <x-app.stat-strip :items="[
            ['icon' => 'academic-cap', 'label' => __('dashboard.stat_courses_in_progress'), 'value' => $stats['courses_in_progress']],
            ['icon' => 'document-check', 'label' => __('dashboard.stat_certificates_earned'), 'value' => $stats['certificates_earned']],
            ['icon' => 'chat-bubble-left-right', 'label' => __('dashboard.stat_consultations'), 'value' => $stats['consultations']],
        ]" />

        <div class="grid gap-6 lg:grid-cols-3 lg:gap-8">
            <div class="flex min-w-0 flex-col gap-6 lg:col-span-2">
                <section aria-labelledby="dash-learning">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <h2 id="dash-learning" class="heading-4 flex items-center gap-2"><span class="star-mark text-base text-secondary-text" aria-hidden="true"></span>{{ __('dashboard.continue_learning') }}</h2>
                        @if ($hasCourses)
                            <a href="{{ route('dashboard.courses.index', $locale) }}" wire:navigate class="link-underline text-sm font-semibold text-link">{{ __('dash_home_ui.view_all') }}</a>
                        @endif
                    </div>

                    @if (! $hasCourses)
                        <x-empty-state illustration="courses" icon="academic-cap" :title="__('dashboard.no_courses_in_progress')" :message="__('dash_home_ui.empty_courses_hint')">
                            <x-slot name="action">
                                <x-button :href="route('courses.index', $locale)" variant="primary">{{ __('dashboard.browse_courses_cta') }}</x-button>
                            </x-slot>
                        </x-empty-state>
                    @else
                        <div class="grid gap-4 sm:grid-cols-2">
                            @foreach ($inProgress as $enrollment)
                                @php
                                $pd = $progressData[$enrollment->id] ?? ['total' => 0, 'done' => 0, 'next' => null];
                                $hasModules = $enrollment->course->modules()->exists();
                                $learn = $hasModules
                                    ? route('dashboard.courses.study', ['locale' => $locale, 'course' => $enrollment->course->slug])
                                    : route('dashboard.courses.learn', ['locale' => $locale, 'course' => $enrollment->course->slug]);
                                $nextUrl = (! $hasModules && $pd['next']) ? route('dashboard.courses.lesson', ['locale' => $locale, 'course' => $enrollment->course->slug, 'lesson' => $pd['next']->uuid]) : $learn;
                                @endphp
                                <article class="card-surface card-hover group relative flex min-w-0 flex-col gap-4 overflow-hidden p-5">
                                    <span class="glow-teal opacity-40" style="--glow-size: 10rem; inset-inline-end: -3rem; top: -4rem" aria-hidden="true"></span>
                                    <div class="flex items-start gap-4">
                                        <x-progress-ring :percent="$enrollment->progress_percent" :size="68" :stroke="7" :label="$enrollment->course->title" />
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-display text-lg font-semibold leading-snug text-strong break-words">
                                                <a href="{{ $learn }}" wire:navigate class="after:absolute after:inset-0">{{ $enrollment->course->title }}</a>
                                            </h3>
                                            <p class="mt-1 text-sm text-body">{{ __('dash_home_ui.lessons_done', ['done' => $pd['done'], 'total' => $pd['total']]) }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-auto flex items-center justify-between gap-3 rounded-xl bg-tint px-3 py-2.5">
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold text-secondary-text">{{ $pd['next'] ? __('dash_home_ui.next_lesson') : __('dash_home_ui.all_lessons_done') }}</p>
                                            @if ($pd['next'])
                                                <p class="truncate text-sm font-medium text-strong">{{ $pd['next']->title }}</p>
                                            @endif
                                        </div>
                                        <a href="{{ $nextUrl }}" wire:navigate class="tap-target relative z-raised inline-flex shrink-0 items-center justify-center rounded-full bg-brand text-on-brand transition duration-fast active:scale-95 [@media(hover:hover)]:hover:bg-brand-hover" aria-label="{{ __('dash_home_ui.continue') }}: {{ $enrollment->course->title }}">
                                            <x-icon name="arrow-right" class="size-5 rtl:-scale-x-100" aria-hidden="true" />
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </section>

                @if ($recommended)
                    <section aria-labelledby="dash-reco" class="gradient-border relative overflow-hidden rounded-panel bg-surface-raised p-5 sm:p-6">
                        <span class="bg-pattern-dots absolute inset-0 -z-0 opacity-50" aria-hidden="true"></span>
                        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0 space-y-2">
                                <p id="dash-reco" class="eyebrow inline-flex items-center gap-2 text-secondary-text"><span class="star-mark text-sm" aria-hidden="true"></span>{{ __('dash_home_ui.recommended') }}</p>
                                <h3 class="heading-3 break-words">{{ $recommended->title }}</h3>
                                <div class="flex flex-wrap items-center gap-2">
                                    @if ($recommended->is_free)
                                        <x-badge color="success" :text="__('dash_home_ui.course_free')" />
                                    @endif
                                    @if ($recommended->estimated_duration_hours)
                                        <x-badge color="neutral" icon="clock" :text="__('dash_home_ui.course_hours', ['count' => $recommended->estimated_duration_hours])" />
                                    @endif
                                </div>
                            </div>
                            <x-button :href="route('courses.show', ['locale' => $locale, 'slug' => $recommended->slug])" variant="primary" icon-end="arrow-right" class="shrink-0">{{ __('dash_home_ui.recommended_cta') }}</x-button>
                        </div>
                    </section>
                @endif
            </div>

            <div class="flex min-w-0 flex-col gap-6">
                <section aria-labelledby="dash-actions" class="card-surface p-5">
                    <h2 id="dash-actions" class="heading-4 mb-3">{{ __('dash_home_ui.quick_actions') }}</h2>
                    <ul class="grid gap-1">
                        @foreach ([
                            ['consultation.show', 'chat-bubble-left-right', 'qa_ask'],
                            ['dashboard.courses.index', 'academic-cap', 'qa_courses'],
                            ['dashboard.certificates.index', 'document-check', 'qa_certificates'],
                            ['certificates.verify.form', 'shield-check', 'qa_verify'],
                        ] as [$route, $icon, $key])
                            <li class="min-w-0">
                                <a href="{{ route($route, $locale) }}" wire:navigate class="group flex min-h-14 w-full items-center gap-3 rounded-xl px-2 py-2 transition duration-fast active:scale-[0.99] [@media(hover:hover)]:hover:bg-tint">
                                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-tint text-brand-primary"><x-icon :name="$icon" class="size-5" aria-hidden="true" /></span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-sm font-semibold text-strong">{{ __('dash_home_ui.'.$key) }}</span>
                                        <span class="block truncate text-xs text-body">{{ __('dash_home_ui.'.$key.'_hint') }}</span>
                                    </span>
                                    <x-icon name="chevron-right" class="size-4 shrink-0 text-muted rtl:-scale-x-100" aria-hidden="true" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <section aria-labelledby="dash-recent" class="card-surface p-5">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <h2 id="dash-recent" class="heading-4">{{ __('dashboard.recent_consultations') }}</h2>
                        @if ($recentConsultations->isNotEmpty())
                            <a href="{{ route('dashboard.consultations.index', $locale) }}" wire:navigate class="link-underline text-sm font-semibold text-link">{{ __('dash_home_ui.view_all') }}</a>
                        @endif
                    </div>

                    @if ($recentConsultations->isEmpty())
                        <x-empty-state compact icon="chat-bubble-left-right" :title="__('dashboard.no_consultations_yet')" :message="__('dash_home_ui.timeline_empty_hint')">
                            <x-slot name="action">
                                <x-button :href="route('consultation.show', $locale)" variant="primary" size="sm">{{ __('dashboard.ask_question_cta') }}</x-button>
                            </x-slot>
                        </x-empty-state>
                    @else
                        <ol class="relative space-y-5 ps-6 before:absolute before:inset-y-1 before:start-[0.6rem] before:w-px before:bg-border-strong">
                            @foreach ($recentConsultations as $consultation)
                                <li class="relative min-w-0">
                                    <span class="star-mark absolute -start-6 top-0.5 text-xl" aria-hidden="true"></span>
                                    <a href="{{ route('dashboard.consultations.index', $locale) }}" wire:navigate class="block min-w-0 rounded-lg outline-none focus-visible:ring-4 focus-visible:ring-brand/30">
                                        <p class="break-words text-sm font-semibold text-strong">{{ $consultation->topic ?: __('dash_home_ui.consultation_no_topic') }}</p>
                                        <div class="mt-1 flex flex-wrap items-center gap-2">
                                            <x-badge size="sm" :color="$statusColors[$consultation->status->value] ?? 'neutral'" :text="__('enums.consultation_status.'.$consultation->status->value)" />
                                            <time datetime="{{ $consultation->created_at->toDateString() }}" class="text-xs text-body">{{ $consultation->created_at->translatedFormat('M d, Y') }}</time>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </section>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
