<div>
    <x-slot name="seo">
        <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" />
        <meta name="robots" content="noindex, nofollow">
    </x-slot>

    @php
        $locale = app()->getLocale();
        $done = \App\Support\Enums\EnrollmentStatus::COMPLETED;
        $tabs = [
            'all' => ['label' => __('dashboard.tab_all'), 'icon' => 'squares-2x2'],
            'in_progress' => ['label' => __('dashboard.tab_in_progress'), 'icon' => 'play-circle'],
            'completed' => ['label' => __('dashboard.tab_completed'), 'icon' => 'check-badge'],
        ];
    @endphp

    <div class="mx-auto w-full max-w-6xl space-y-6 px-4 py-6 md:px-6 md:py-8">
        <section class="relative isolate overflow-hidden rounded-panel border border-subtle bg-hero-gradient p-5 sm:p-8">
            <span class="glow-teal -z-10" style="--glow-size: 22rem; inset-inline-end: -6rem; top: -8rem" aria-hidden="true"></span>
            <span class="glow-gold -z-10" style="--glow-size: 14rem; inset-inline-start: -4rem; bottom: -7rem" aria-hidden="true"></span>
            <span class="star-mark pointer-events-none absolute -end-6 -top-6 -z-10 text-[9rem] opacity-15 sm:text-[12rem]" aria-hidden="true"></span>

            <x-app.page-header :title="__('dashboard.my_courses_title')" :eyebrow="__('dash_learn_ui.mc_eyebrow')" :description="__('dash_learn_ui.mc_subtitle')" />

            <div class="no-scrollbar mt-6 flex items-center gap-2 overflow-x-auto sm:gap-3" role="tablist" aria-label="{{ __('dash_learn_ui.tabs_label') }}">
                @foreach ($tabs as $key => $t)
                    @php $active = $this->tab === $key; @endphp
                    <button
                        type="button"
                        role="tab"
                        wire:click="setTab('{{ $key }}')"
                        aria-selected="{{ $active ? 'true' : 'false' }}"
                        class="focus-ring inline-flex min-h-11 shrink-0 items-center gap-2 rounded-xl border px-3.5 text-sm sm:px-4 font-semibold transition duration-fast ease-enter active:scale-[0.98] {{ $active ? 'border-brand bg-brand text-on-brand shadow-soft' : 'border-subtle bg-surface-raised text-body [@media(hover:hover)]:hover:border-strong [@media(hover:hover)]:hover:text-strong' }}"
                    >
                        <x-icon :name="$t['icon']" class="hidden size-4 sm:block" aria-hidden="true" />
                        <span>{{ $t['label'] }}</span>
                        <span class="min-w-6 rounded-full px-1.5 font-bold text-center text-xs tabular-nums {{ $active ? 'bg-white/20 text-on-brand' : 'bg-tint text-strong' }}">{{ $counts[$key] }}</span>
                    </button>
                @endforeach
            </div>
        </section>

        <div wire:loading.delay.short wire:target="setTab" class="grid gap-4 md:grid-cols-2" aria-hidden="true">
            @foreach ([1, 2] as $i)
                <div class="card-surface overflow-hidden">
                    <div class="skeleton h-32"></div>
                    <div class="space-y-3 p-5">
                        <div class="skeleton h-5 w-2/3 rounded"></div>
                        <div class="skeleton h-4 w-1/3 rounded"></div>
                    </div>
                </div>
            @endforeach
        </div>

        <div wire:loading.remove.delay.short wire:target="setTab" role="tabpanel">
            @if ($enrollments->isEmpty())
                @if (! $hasAnyEnrollment)
                    <x-empty-state icon="academic-cap" illustration="courses" :title="__('dashboard.no_enrollments_title')" :message="__('dash_learn_ui.empty_all_msg')">
                        <x-slot name="action">
                            <x-button :href="route('courses.index', $locale)" navigate variant="primary" icon="academic-cap">{{ __('dashboard.browse_courses_cta') }}</x-button>
                        </x-slot>
                    </x-empty-state>
                @elseif ($tab === 'completed')
                    <x-empty-state icon="check-badge" :title="__('dash_learn_ui.empty_completed_title')" :message="__('dash_learn_ui.empty_completed_msg')">
                        <x-slot name="action">
                            <x-button type="button" wire:click="setTab('in_progress')" variant="primary">{{ __('dashboard.tab_in_progress') }}</x-button>
                            <x-button type="button" wire:click="setTab('all')" variant="ghost">{{ __('dash_learn_ui.show_all') }}</x-button>
                        </x-slot>
                    </x-empty-state>
                @else
                    <x-empty-state icon="sparkles" :title="__('dashboard.no_courses_in_progress')" :message="__('dash_learn_ui.empty_progress_msg')">
                        <x-slot name="action">
                            <x-button :href="route('courses.index', $locale)" navigate variant="primary">{{ __('dashboard.browse_courses_cta') }}</x-button>
                            <x-button type="button" wire:click="setTab('all')" variant="ghost">{{ __('dash_learn_ui.show_all') }}</x-button>
                        </x-slot>
                    </x-empty-state>
                @endif
            @else
                <ul class="grid gap-4 md:grid-cols-2 xl:gap-5">
                    @foreach ($enrollments as $enrollment)
                        @php
                            $course = $enrollment->course;
                            $isDone = $enrollment->status === $done;
                            $total = (int) ($course->lessons_count ?? 0);
                            $doneCount = $isDone ? $total : (int) round($total * $enrollment->progress_percent / 100);
                            $cover = $course->getFirstMediaUrl('cover_image', 'card');
                            $learnUrl = route('dashboard.courses.learn', ['locale' => $locale, 'course' => $course->slug]);
                        @endphp
                        <li class="min-w-0" wire:key="enr-{{ $enrollment->id }}">
                            <article class="card-surface card-hover group relative flex h-full flex-col overflow-hidden {{ $isDone ? 'gradient-border-gold' : '' }}">
                                <div class="relative h-32 shrink-0 overflow-hidden bg-hero-gradient sm:h-36">
                                    @if ($cover)
                                        <div class="img-zoom size-full">
                                            <img src="{{ $cover }}" alt="" width="600" height="400" loading="lazy" decoding="async" class="size-full object-cover">
                                        </div>
                                        <span class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent" aria-hidden="true"></span>
                                    @else
                                        <span class="bg-pattern-islamic absolute inset-0" aria-hidden="true"></span>
                                        <span class="star-mark absolute end-4 top-4 text-6xl opacity-40" aria-hidden="true"></span>
                                    @endif
                                    <span class="absolute start-3 top-3 inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold shadow-soft {{ $isDone ? 'bg-brand-secondary text-on-secondary' : 'bg-surface-raised text-strong' }}">
                                        <x-icon :name="$isDone ? 'check-badge' : 'play-circle'" class="size-4" aria-hidden="true" />
                                        {{ $isDone ? __('dash_learn_ui.status_done') : __('dash_learn_ui.status_active') }}
                                    </span>
                                </div>

                                <div class="relative flex flex-1 flex-col gap-4 p-5">
                                    <div class="absolute -top-9 end-5 rounded-full bg-surface-raised p-1 shadow-lift"><x-progress-ring :percent="$enrollment->progress_percent" :size="72" :stroke="7" :label="__('polish_forms.progress')" /></div>

                                    <div class="min-w-0 pe-20">
                                        <h2 class="heading-4 line-clamp-2 break-words text-strong">{{ $course->title }}</h2>
                                        <p class="mt-1.5 text-sm text-body">
                                            {{ $total > 0 ? __('dash_learn_ui.lessons_progress', ['done' => $doneCount, 'total' => $total]) : __('dash_learn_ui.lessons_count', ['count' => 0]) }}
                                        </p>
                                        <p class="mt-1 text-xs text-muted">
                                            @if ($isDone && $enrollment->completed_at)
                                                {{ __('dash_learn_ui.completed_on', ['date' => $enrollment->completed_at->translatedFormat('j M Y')]) }}
                                            @elseif ($enrollment->enrolled_at)
                                                {{ __('dash_learn_ui.enrolled_on', ['date' => $enrollment->enrolled_at->translatedFormat('j M Y')]) }}
                                            @endif
                                        </p>
                                    </div>

                                    <div class="mt-auto flex flex-wrap items-center gap-2 pt-1">
                                        <x-button :href="$learnUrl" navigate :variant="$isDone ? 'outline' : 'primary'" size="md" :icon-end="$isDone ? 'arrow-path' : 'arrow-right'">
                                            {{ $isDone ? __('dashboard.review_btn') : __('dashboard.continue_btn') }}
                                        </x-button>
                                        @if ($isDone)
                                            <x-button :href="route('dashboard.certificates.index', $locale)" navigate variant="soft" icon="document-check" :label="__('dashboard.view_certificate')">
                                                {{ __('dash_learn_ui.certificate_ready') }}
                                            </x-button>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
