@php
$locale = app()->getLocale();
$cover = $course->getFirstMediaUrl('cover_image', 'hero');
$fallback = asset('images/course-placeholder.svg');
$lessons = $course->lessons;
$lessonCount = $lessons->count();
$totalMinutes = (int) $lessons->sum('duration_minutes');
$modules = $course->modules()->with('blocks')->get();
$moduleCount = $modules->count();
$blockCount = $modules->sum(fn ($m) => $m->blocks->count());
// Prefer the new module/block curriculum whenever it exists -- a course
// migrated to modules keeps its old flat lessons as leftover data, but
// the lessons view must not take precedence over the course-builder UI.
if ($moduleCount) {
    $lessonCount = 0;
}
$blockTypeIcons = [
    'reading' => 'book-open',
    'research_reading' => 'book-open',
    'practical_quiz' => 'puzzle-piece',
    'graded_quiz' => 'clipboard-document-check',
    'case_study' => 'document-magnifying-glass',
    'research_paper' => 'document-magnifying-glass',
    'case_analysis' => 'document-magnifying-glass',
    'discussion' => 'chat-bubble-left-right',
    'assignment' => 'paper-clip',
    'research_activity' => 'beaker',
];
$levelLabel = __('enums.course_level.'.$course->level->value);
$audienceLabel = __('enums.course_audience.'.$course->audience->value);
$outcomes = collect($course->learning_outcomes ?? [])->filter()->values();
$facts = array_filter([
    ['icon' => 'banknotes', 'label' => __('courses_ui.fact_price'), 'value' => $course->is_free ? __('common.free') : ($course->formattedPrice() ?? __('common.paid'))],
    $course->estimated_duration_hours ? ['icon' => 'clock', 'label' => __('courses_ui.fact_duration'), 'value' => trans_choice('kit_sections.hours', $course->estimated_duration_hours, ['count' => $course->estimated_duration_hours])] : null,
    $lessonCount ? ['icon' => 'play-circle', 'label' => __('courses_ui.fact_lessons'), 'value' => $lessonCount] : null,
    (! $lessonCount && $moduleCount) ? ['icon' => 'play-circle', 'label' => __('courses_ui.fact_lessons'), 'value' => $blockCount] : null,
    ['icon' => 'chart-bar', 'label' => __('courses_ui.fact_level'), 'value' => $levelLabel],
    ['icon' => 'user-group', 'label' => __('courses_ui.fact_audience'), 'value' => $audienceLabel],
    ['icon' => 'users', 'label' => __('courses_ui.fact_learners'), 'value' => number_format((int) $course->enrolled_count)],
]);
$learnUrl = route('dashboard.courses.learn', ['locale' => $locale, 'course' => $course->slug]);
$enrollUrl = route('courses.enroll', ['locale' => $locale, 'slug' => $course->slug]);
$faq = [
    ['q' => __('courses_ui.faq_who_q'), 'a' => __('courses_ui.faq_who_a', ['audience' => $audienceLabel, 'level' => $levelLabel])],
    ['q' => __('courses_ui.faq_how_q'), 'a' => __('courses_ui.faq_how_a')],
    ['q' => __('courses_ui.faq_track_q'), 'a' => __('courses_ui.faq_track_a')],
];
@endphp

<x-layouts.public>
    <x-slot name="seo">
        <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" />
    </x-slot>

    <div class="pb-24 lg:pb-0">
        {{-- Banner hero --}}
        <section class="bg-section-dark relative isolate overflow-hidden">
            @if ($cover)<img src="{{ $cover }}" alt="" class="absolute inset-0 -z-10 size-full object-cover opacity-20 blur-sm" aria-hidden="true">@endif
            <span class="bg-pattern-islamic pointer-events-none absolute inset-0 -z-10" aria-hidden="true"></span>
            <div class="container-page grid items-center gap-8 pb-28 pt-8 sm:pt-10 lg:grid-cols-12 lg:gap-12 lg:pb-32">
                <div class="min-w-0 lg:col-span-7">
                    <x-breadcrumbs :items="[
                        ['label' => __('nav.courses'), 'url' => route('courses.index', $locale)],
                        ['label' => $course->title],
                    ]" />
                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        <x-badge color="accent" variant="solid" :text="$levelLabel" />
                        <x-badge color="neutral" :text="$audienceLabel" />
                        <x-badge :color="$course->is_free ? 'success' : 'warning'" variant="solid" :text="$course->is_free ? __('common.free') : ($course->formattedPrice() ?? __('common.paid'))" />
                    </div>
                    <h1 class="heading-1 mt-4 break-words">{{ $course->title }}</h1>
                    <p class="lead mt-4 max-w-2xl break-words">{{ $course->short_description }}</p>
                    <div class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-body">
                        <x-level-meter :level="$course->level" />
                        @if ($lessonCount)<span class="inline-flex items-center gap-1.5"><x-icon name="play-circle" class="size-4" aria-hidden="true" />{{ trans_choice('kit_sections.lessons', $lessonCount, ['count' => $lessonCount]) }}</span>@elseif ($moduleCount)<span class="inline-flex items-center gap-1.5"><x-icon name="play-circle" class="size-4" aria-hidden="true" />{{ trans_choice('kit_sections.modules', $moduleCount, ['count' => $moduleCount]) }}</span>@endif
                        <span class="inline-flex items-center gap-1.5"><x-icon name="users" class="size-4" aria-hidden="true" />{{ trans_choice('kit_sections.learners', (int) $course->enrolled_count, ['count' => number_format((int) $course->enrolled_count)]) }}</span>
                    </div>
                    @if ($lessonCount || $moduleCount)
                        <a href="#curriculum" class="focus-ring link-underline mt-5 inline-flex min-h-11 items-center gap-1.5 text-sm font-semibold text-link">{{ __('courses_ui.jump') }}<x-icon name="arrow-down" class="size-4" aria-hidden="true" /></a>
                    @endif
                </div>
                <div class="relative hidden min-w-0 lg:col-span-5 lg:block">
                    <span class="star-mark pointer-events-none absolute -start-6 -top-6 text-9xl opacity-25" aria-hidden="true"></span>
                    <div class="gradient-border-gold relative aspect-[3/2] overflow-hidden !rounded-panel shadow-float">
                        <img src="{{ $cover ?: $fallback }}" alt="{{ $course->title }}" width="1200" height="630" fetchpriority="high" decoding="async" onerror="this.onerror=null;this.src='{{ $fallback }}'" class="size-full object-cover">
                    </div>
                </div>
            </div>
            <span class="gold-thread absolute inset-x-0 bottom-0" aria-hidden="true"></span>
        </section>

        <div class="container-page grid gap-8 pb-12 lg:grid-cols-12 lg:gap-10 lg:pb-20">
            {{-- Enroll card --}}
            <aside class="relative z-raised -mt-20 min-w-0 lg:order-2 lg:col-span-4 lg:-mt-24" aria-label="{{ __('courses_ui.facts_title') }}">
                <div class="card-surface gradient-border !rounded-panel p-5 shadow-float sm:p-6 lg:sticky lg:top-24">
                    <div class="mb-5 overflow-hidden rounded-xl lg:hidden">
                        <img src="{{ $cover ?: $fallback }}" alt="" width="1200" height="630" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='{{ $fallback }}'" class="aspect-[16/9] w-full object-cover">
                    </div>
                    <p class="numeral-display text-4xl text-strong">{{ $course->is_free ? __('common.free') : ($course->formattedPrice() ?? __('common.paid')) }}</p>
                    <div class="mt-5">
                        @auth
                            @if ($isEnrolled)
                                <x-button :href="$learnUrl" variant="primary" size="lg" block icon-end="arrow-right">{{ __('courses.continue_learning') }}</x-button>
                                <p class="mt-3 flex items-center gap-2 text-sm text-body"><x-icon name="check-badge" class="size-5 shrink-0 text-success" aria-hidden="true" />{{ __('courses_ui.enrolled_hint') }}</p>
                            @else
                                <form method="POST" action="{{ $enrollUrl }}">
                                    @csrf
                                    <x-button type="submit" variant="primary" size="lg" block>{{ __('courses.enroll_now') }}</x-button>
                                </form>
                                <p class="mt-3 text-sm text-body">{{ __('courses_ui.enroll_hint') }}</p>
                            @endif
                        @else
                            <x-button :href="route('login', $locale)" variant="primary" size="lg" block>{{ __('courses.login_to_enroll') }}</x-button>
                            <p class="mt-3 text-sm text-body">{{ __('courses_ui.guest_hint') }}</p>
                        @endauth
                    </div>
                    <div class="mt-6 border-t pt-5">
                    <h2 class="eyebrow mb-3">{{ __('courses_ui.facts_title') }}</h2>
                    <dl class="space-y-3">
                        @foreach ($facts as $fact)
                            <div class="flex items-center justify-between gap-3 text-sm">
                                <dt class="inline-flex min-w-0 items-center gap-2 text-muted"><x-icon :name="$fact['icon']" class="size-5 shrink-0 text-brand-primary" aria-hidden="true" />{{ $fact['label'] }}</dt>
                                <dd class="min-w-0 break-words text-end font-semibold text-strong">{{ $fact['value'] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                    </div>
                </div>
            </aside>

            <div class="min-w-0 space-y-12 lg:order-1 lg:col-span-8 lg:pt-12">
                @if (filled(strip_tags((string) $course->full_description)))
                    <section aria-labelledby="about-h">
                        <h2 id="about-h" class="heading-2 mb-4">{{ __('courses_ui.about') }}</h2>
                        <div class="prose-content max-w-none break-words">{!! $course->full_description !!}</div>
                    </section>
                @endif

                @if ($outcomes->isNotEmpty())
                    <section aria-labelledby="learn-h" class="card-surface !rounded-panel p-5 sm:p-8">
                        <h2 id="learn-h" class="heading-2 mb-5">{{ __('courses.what_youll_learn') }}</h2>
                        <ul class="grid gap-3 sm:grid-cols-2">
                            @foreach ($outcomes as $outcome)
                                <li class="flex min-w-0 items-start gap-3 rounded-xl bg-tint p-3 text-body">
                                    <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0 text-success" aria-hidden="true" />
                                    <span class="min-w-0 break-words">{{ $outcome }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                <section id="curriculum" aria-labelledby="curr-h" class="scroll-mt-28">
                    <div class="mb-5 flex flex-wrap items-end justify-between gap-2">
                        <h2 id="curr-h" class="heading-2">{{ __('courses.curriculum') }}</h2>
                        @if ($lessonCount)
                            <p class="text-sm text-muted">{{ trans_choice('kit_sections.lessons', $lessonCount, ['count' => $lessonCount]) }}@if ($totalMinutes) &middot; {{ __('polish_public.lesson_minutes', ['count' => $totalMinutes]) }}@endif</p>
                        @elseif ($moduleCount)
                            <p class="text-sm text-muted">{{ trans_choice('kit_sections.modules', $moduleCount, ['count' => $moduleCount]) }} &middot; {{ trans_choice('kit_sections.blocks', $blockCount, ['count' => $blockCount]) }}</p>
                        @endif
                    </div>
                    @if ($lessonCount)
                        <div x-data="{ open: [] }" class="space-y-3">
                            @foreach ($lessons as $i => $lesson)
                                @php $unlocked = $lesson->is_preview || $isEnrolled; @endphp
                                <div class="overflow-hidden rounded-2xl border bg-surface-raised transition-[border-color,box-shadow] duration-base" x-bind:class="open.includes({{ $i }}) ? 'border-brand/40 shadow-soft' : 'border-subtle'">
                                    <h3>
                                        <button type="button" id="lesson-h-{{ $i }}" aria-controls="lesson-p-{{ $i }}" x-bind:aria-expanded="open.includes({{ $i }}).toString()"
                                            x-on:click="open = open.includes({{ $i }}) ? open.filter(x => x !== {{ $i }}) : [...open, {{ $i }}]"
                                            class="focus-ring flex min-h-14 w-full items-center gap-3 px-4 py-3 text-start sm:px-5 [@media(hover:hover)]:hover:bg-tint">
                                            <span class="numeral-display w-8 shrink-0 text-lg text-secondary-text">{{ $i + 1 }}</span>
                                            <span class="min-w-0 flex-1">
                                                <span class="block break-words font-semibold leading-snug text-strong">{{ $lesson->title }}</span>
                                                <span class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-muted">
                                                    @if ($lesson->duration_minutes)<span class="inline-flex items-center gap-1"><x-icon name="clock" class="size-3.5" aria-hidden="true" />{{ __('polish_public.lesson_minutes', ['count' => $lesson->duration_minutes]) }}</span>@endif
                                                    @if ($lesson->is_preview)<x-badge color="success" size="sm" icon="eye" :text="__('courses_ui.free_preview')" />@endif
                                                </span>
                                            </span>
                                            @if (! $unlocked)<x-icon name="lock-closed" class="size-5 shrink-0 text-muted" aria-hidden="true" /><span class="sr-only">{{ __('courses_ui.locked') }}</span>@endif
                                            <x-icon name="chevron-down" class="size-5 shrink-0 text-muted transition-transform duration-base ease-enter" x-bind:class="open.includes({{ $i }}) && 'rotate-180'" aria-hidden="true" />
                                        </button>
                                    </h3>
                                    <div id="lesson-p-{{ $i }}" role="region" aria-labelledby="lesson-h-{{ $i }}" x-show="open.includes({{ $i }})" x-collapse x-cloak>
                                        <div class="break-words px-4 pb-5 ps-12 text-body sm:px-5 sm:ps-16">
                                            @if ($unlocked)
                                                @if (filled($lesson->body))<div class="prose-content max-w-none">{!! nl2br(e(strip_tags((string) $lesson->body))) !!}</div>@else<p class="text-muted">{{ __('courses_ui.no_body') }}</p>@endif
                                            @else
                                                <p class="flex items-center gap-2 text-sm"><x-icon name="lock-closed" class="size-4 shrink-0" aria-hidden="true" />
                                                    @auth {{ __('courses_ui.locked') }} @else <a href="{{ route('login', $locale) }}" wire:navigate class="link-underline font-semibold text-link">{{ __('courses.login_to_enroll') }}</a> @endauth
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif ($moduleCount)
                        <div x-data="{ open: [] }" class="space-y-3">
                            @foreach ($modules as $i => $module)
                                <div class="overflow-hidden rounded-2xl border border-subtle bg-surface-raised transition-[border-color,box-shadow] duration-base" x-bind:class="open.includes({{ $i }}) ? 'border-brand/40 shadow-soft' : 'border-subtle'">
                                    <h3>
                                        <button type="button" id="module-h-{{ $i }}" aria-controls="module-p-{{ $i }}" x-bind:aria-expanded="open.includes({{ $i }}).toString()"
                                            x-on:click="open = open.includes({{ $i }}) ? open.filter(x => x !== {{ $i }}) : [...open, {{ $i }}]"
                                            class="focus-ring flex min-h-14 w-full items-center gap-3 px-4 py-3 text-start sm:px-5 [@media(hover:hover)]:hover:bg-tint">
                                            <span class="numeral-display w-8 shrink-0 text-lg text-secondary-text">{{ $i + 1 }}</span>
                                            <span class="min-w-0 flex-1">
                                                <span class="block break-words font-semibold leading-snug text-strong">{{ $module->title }}</span>
                                                <span class="mt-1 block text-xs text-muted">{{ trans_choice('kit_sections.blocks', $module->blocks->count(), ['count' => $module->blocks->count()]) }}</span>
                                            </span>
                                            <x-icon name="chevron-down" class="size-5 shrink-0 text-muted transition-transform duration-base ease-enter" x-bind:class="open.includes({{ $i }}) && 'rotate-180'" aria-hidden="true" />
                                        </button>
                                    </h3>
                                    <div id="module-p-{{ $i }}" role="region" aria-labelledby="module-h-{{ $i }}" x-show="open.includes({{ $i }})" x-collapse x-cloak>
                                        <ul class="break-words px-4 pb-5 ps-12 text-body sm:px-5 sm:ps-16">
                                            @foreach ($module->blocks as $block)
                                                <li class="flex min-w-0 items-center gap-2.5 py-1.5 text-sm">
                                                    <x-icon :name="$blockTypeIcons[$block->type] ?? 'document-text'" class="size-4 shrink-0 text-muted" aria-hidden="true" />
                                                    <span class="min-w-0 flex-1 truncate">{{ $block->title }}</span>
                                                    @if ($block->is_preview)
                                                        <x-badge color="success" size="sm" icon="eye" :text="__('courses_ui.free_preview')" />
                                                    @elseif (! $isEnrolled)
                                                        <x-icon name="lock-closed" class="size-4 shrink-0 text-muted" aria-hidden="true" /><span class="sr-only">{{ __('courses_ui.locked') }}</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <x-empty-state icon="book-open" compact :title="__('courses_ui.no_lessons')" />
                    @endif
                </section>

                @if ($course->instructor)
                    <section aria-labelledby="inst-h">
                        <h2 id="inst-h" class="heading-2 mb-4">{{ __('courses.instructor') }}</h2>
                        @if ($course->instructor->hasRole('director'))
                            <x-director-profile :compact="true" />
                        @else
                            <x-instructor-card :instructor="$course->instructor" />
                        @endif
                    </section>
                @endif

                <section aria-labelledby="faq-h">
                    <h2 id="faq-h" class="heading-2 mb-4">{{ __('courses_ui.faq_title') }}</h2>
                    <x-faq :items="$faq" :open="0" class="!max-w-none" />
                </section>
            </div>
        </div>

        @if ($related->isNotEmpty())
            <x-section variant="tinted" thread>
                <x-section-heading :title="__('courses_ui.more_title')" :url="route('courses.index', $locale)" :link-label="__('courses_ui.all_courses')" />
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        <x-course-card :course="$item" />
                    @endforeach
                </div>
            </x-section>
        @endif

        {{-- Mobile sticky CTA --}}
        <div class="pb-safe fixed inset-x-0 bottom-0 z-sticky border-t bg-surface-raised/95 px-4 pt-3 shadow-float backdrop-blur-md lg:hidden" style="--safe-pad: 0.75rem">
            <div class="mx-auto flex max-w-xl items-center gap-3 pe-14">
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-strong">{{ $course->title }}</p>
                    <p class="text-xs text-muted">{{ $course->is_free ? __('common.free') : ($course->formattedPrice() ?? __('common.paid')) }}</p>
                </div>
                @auth
                    @if ($isEnrolled)
                        <x-button :href="$learnUrl" variant="primary" class="shrink-0">{{ __('courses.continue_learning') }}</x-button>
                    @else
                        <form method="POST" action="{{ $enrollUrl }}" class="shrink-0">
                            @csrf
                            <x-button type="submit" variant="primary">{{ __('courses.enroll_now') }}</x-button>
                        </form>
                    @endif
                @else
                    <x-button :href="route('login', $locale)" variant="primary" class="shrink-0">{{ __('courses.login_to_enroll') }}</x-button>
                @endauth
            </div>
        </div>
    </div>
</x-layouts.public>
