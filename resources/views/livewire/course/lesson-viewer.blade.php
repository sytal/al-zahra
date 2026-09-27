<div
    x-data="{
        menu: false,
        help: false,
        desk: window.matchMedia('(min-width: 1024px)').matches,
        celebrate: false,
        typing(e) {
            const t = e.target;
            return t && (t.isContentEditable || ['INPUT', 'TEXTAREA', 'SELECT'].includes(t.tagName));
        },
        key(e) {
            if (e.ctrlKey || e.metaKey || e.altKey || this.typing(e)) return;
            const k = e.key.toLowerCase();
            if (k === 'n') this.$refs.next?.click();
            else if (k === 'p') this.$refs.prev?.click();
            else if (k === 'c') this.$refs.complete?.click();
            else if (k === 'm') this.menu = !this.menu;
            else if (k === '?') this.help = !this.help;
            else if (k === 'escape') { this.menu = false; this.help = false; }
        },
        done(e) {
            const d = Array.isArray(e.detail) ? e.detail[0] : e.detail;
            this.celebrate = true;
            this.$dispatch('star-burst');
            if (d && d.courseDone) this.$dispatch('confetti');
        },
    }"
    x-init="const mq = window.matchMedia('(min-width: 1024px)'); mq.addEventListener('change', (e) => { desk = e.matches; if (e.matches) menu = false });"
    x-effect="document.body.classList.toggle('overflow-hidden', menu && !desk)"
    x-on:keydown.window="key($event)"
    x-on:lesson-completed.window="done($event)"
>
    <x-slot name="seo">
        <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" />
        <meta name="robots" content="noindex, nofollow">
    </x-slot>

    @php
        $locale = app()->getLocale();
        $total = $lessons->count();
        $percent = (int) $enrollment->progress_percent;
        $type = $lesson->content_type->value;
        $typeIcon = ['video' => 'video-camera', 'text' => 'document-text', 'mixed' => 'rectangle-stack'][$type] ?? 'document-text';
        $attachments = $lesson->getMedia('lesson_attachments');
        $lessonUrl = fn ($item) => route('dashboard.courses.lesson', ['locale' => $locale, 'course' => $course->slug, 'lesson' => $item->uuid]);
        $showEarned = $courseDone || $courseJustCompleted;
    @endphp

    <span x-data="confetti" x-on:confetti.window="fire()" class="hidden"></span>

    <div class="mx-auto w-full max-w-7xl px-4 pt-4 md:px-6 md:pt-6">
        <x-breadcrumbs :items="[
            ['label' => __('dashboard.nav_my_courses'), 'url' => route('dashboard.courses.index', $locale)],
            ['label' => $course->title, 'url' => route('courses.show', ['locale' => $locale, 'slug' => $course->slug])],
            ['label' => $lesson->title],
        ]" />

        <div class="grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_21rem] xl:gap-8">
            <div class="min-w-0 space-y-5">
                <header class="space-y-3">
                    <p class="eyebrow flex flex-wrap items-center gap-x-3 gap-y-1 text-secondary-text">
                        <span class="inline-flex items-center gap-2"><span class="star-mark text-sm" aria-hidden="true"></span>{{ __('dash_learn_ui.lv_lesson_of', ['n' => $position + 1, 'total' => $total]) }}</span>
                        <span class="inline-flex items-center gap-1.5 text-muted"><x-icon :name="$typeIcon" class="size-4" aria-hidden="true" />{{ __('dash_learn_ui.lv_type_'.$type) }}</span>
                        @if ($lesson->duration_minutes)
                            <span class="inline-flex items-center gap-1.5 text-muted"><x-icon name="clock" class="size-4" aria-hidden="true" />{{ __('dash_learn_ui.lv_minutes', ['count' => $lesson->duration_minutes]) }}</span>
                        @endif
                    </p>
                    <h1 class="heading-2 break-words text-strong">{{ $lesson->title }}</h1>

                    <div class="flex items-center gap-3">
                        <div class="h-2 flex-1 overflow-hidden rounded-full bg-surface-sunken" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $percent }}" aria-label="{{ __('dash_learn_ui.lv_progress') }}">
                            <div class="h-full rounded-full bg-gradient-to-r from-primary-500 to-secondary transition-[width] duration-slow ease-enter rtl:bg-gradient-to-l motion-reduce:transition-none" style="width: {{ $percent }}%"></div>
                        </div>
                        <span class="shrink-0 text-sm font-bold tabular-nums text-strong">{{ $percent }}%</span>
                    </div>
                </header>

                <article class="card-surface overflow-hidden">
                    @if ($lesson->content_type->value !== 'text' && $lesson->video_url)
                        <div class="relative aspect-video w-full overflow-hidden bg-surface-sunken">
                            <p class="absolute inset-0 flex items-center justify-center p-6 text-center text-sm text-muted">{{ __('dash_learn_ui.lv_video_unavailable') }}</p>
                            <iframe
                                src="{{ $lesson->video_url }}"
                                class="absolute inset-0 size-full"
                                loading="lazy"
                                allow="accelerometer; encrypted-media; picture-in-picture; fullscreen"
                                allowfullscreen
                                referrerpolicy="strict-origin-when-cross-origin"
                                title="{{ $lesson->title }}"
                            ></iframe>
                        </div>
                    @endif

                    @if ($lesson->body)
                        <div class="prose-content max-w-none p-5 sm:p-8">
                            {!! $lesson->body !!}
                        </div>
                    @elseif (! $lesson->video_url)
                        <div class="flex flex-col items-center gap-3 px-5 py-14 text-center">
                            <span class="flex size-14 items-center justify-center rounded-2xl bg-tint text-brand-primary"><x-icon name="document-text" class="size-7" aria-hidden="true" /></span>
                            <p class="text-sm text-body">{{ __('dash_learn_ui.lv_no_content') }}</p>
                        </div>
                    @endif
                </article>

                @if ($attachments->isNotEmpty())
                    <section aria-labelledby="lv-att" class="space-y-3">
                        <h2 id="lv-att" class="heading-4 flex items-center gap-2 text-strong">
                            <span class="star-mark text-sm" aria-hidden="true"></span>{{ __('lessons.attachments') }}
                            <span class="rounded-full bg-tint px-2 text-xs font-bold tabular-nums">{{ $attachments->count() }}</span>
                        </h2>
                        <ul class="grid gap-3 sm:grid-cols-2">
                            @foreach ($attachments as $media)
                                <li class="min-w-0">
                                    <a href="{{ $media->getUrl() }}" target="_blank" rel="noopener" class="card-surface card-hover focus-ring group flex min-h-16 items-center gap-3 p-3">
                                        <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-tint text-brand-primary"><x-icon name="paper-clip" class="size-5" aria-hidden="true" /></span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block truncate text-sm font-semibold text-strong">{{ $media->name }}</span>
                                            <span class="block text-xs uppercase text-muted" dir="ltr">{{ $media->extension }} &middot; {{ $media->human_readable_size }}</span>
                                        </span>
                                        <span class="sr-only">{{ __('dash_learn_ui.lv_open_file') }}</span>
                                        <x-icon name="arrow-top-right-on-square" class="size-5 shrink-0 text-muted transition duration-fast group-hover:text-brand-primary" aria-hidden="true" />
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if ($isCompleted || $showEarned)
                    <section class="relative isolate overflow-hidden rounded-panel {{ $showEarned ? 'gradient-border-gold p-6 sm:p-8' : 'border border-subtle bg-tint p-5' }}" aria-live="polite">
                        <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                            <div class="star-burst relative flex size-16 shrink-0 items-center justify-center rounded-2xl {{ $showEarned ? 'bg-brand-secondary text-on-secondary' : 'bg-success/15 text-success' }}" x-data="starBurst" x-bind:class="{ 'is-bursting': bursting }" x-on:star-burst.window="fire">
                                <x-icon :name="$showEarned ? 'trophy' : 'check-circle'" class="size-8" aria-hidden="true" />
                            </div>
                            <div class="min-w-0 flex-1">
                                @if ($showEarned)
                                    <p class="heading-3 text-strong">{{ __('dash_learn_ui.lv_earned_title') }}</p>
                                    <p class="mt-1 text-body">{{ __('dash_learn_ui.lv_earned_msg') }}</p>
                                @else
                                    <p class="heading-4 text-strong">{{ __('lessons.completed') }}</p>
                                    <p class="mt-1 text-sm text-body">{{ __('dash_learn_ui.lv_done_hint') }}</p>
                                @endif
                            </div>
                            @if ($showEarned)
                                <x-button :href="route('dashboard.certificates.index', $locale)" variant="primary" icon="document-check" class="shrink-0">{{ __('dash_learn_ui.lv_view_cert') }}</x-button>
                            @endif
                        </div>
                        @if ($showEarned)
                            <span class="star-mark pointer-events-none absolute -end-6 -top-6 -z-10 text-[9rem] opacity-15" aria-hidden="true"></span>
                        @endif
                    </section>
                @endif

                <p class="hidden flex-wrap items-center gap-x-5 gap-y-2 text-xs text-muted lg:[@media(hover:hover)]:flex">
                    <span class="font-semibold text-body">{{ __('dash_learn_ui.lv_shortcuts') }}</span>
                    @foreach (['n' => 'lv_key_next', 'p' => 'lv_key_prev', 'c' => 'lv_key_complete', 'm' => 'lv_key_menu'] as $k => $labelKey)
                        <span class="inline-flex items-center gap-1.5"><kbd class="rounded-md border border-strong bg-surface-sunken px-1.5 py-0.5 font-mono text-xs uppercase text-strong">{{ $k }}</kbd>{{ __('dash_learn_ui.'.$labelKey) }}</span>
                    @endforeach
                </p>

                <div class="sticky bottom-[calc(3.5rem+env(safe-area-inset-bottom))] z-sticky -mx-4 border-t border-subtle bg-surface-raised/95 px-4 py-3 shadow-float backdrop-blur md:bottom-0 md:mx-0 md:rounded-2xl md:border md:px-4 lg:bottom-4">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="flex shrink-0 items-center gap-2">
                            @if ($previous)
                                <x-button :href="$lessonUrl($previous)" x-ref="prev" variant="ghost" size="icon" icon="arrow-left" :label="__('dash_learn_ui.lv_prev')" />
                            @endif
                            <x-button type="button" variant="soft" size="icon" icon="list-bullet" :label="__('dash_learn_ui.lv_open_curriculum')" class="lg:hidden" x-on:click="menu = true" x-bind:aria-expanded="menu.toString()" aria-controls="lv-curriculum" />
                        </div>

                        <div class="ms-auto flex min-w-0 flex-1 items-center justify-end gap-2 sm:gap-3">
                            @if ($isCompleted)
                                <span class="inline-flex min-h-11 items-center gap-2 rounded-xl bg-success/15 px-3 text-sm font-semibold text-strong">
                                    <x-icon name="check-circle" class="size-5 text-success" aria-hidden="true" />{{ __('lessons.completed') }}
                                </span>
                            @else
                                <x-button type="button" wire:click="markComplete" x-ref="complete" variant="primary" icon="check" class="min-w-0 flex-1 sm:flex-none">{{ __('lessons.mark_complete') }}</x-button>
                            @endif

                            @if ($courseJustCompleted)
                                <x-button :href="route('dashboard.courses.index', $locale)" variant="outline">{{ __('lessons.back_to_my_courses') }}</x-button>
                            @elseif ($hasNext)
                                <x-button type="button" wire:click="goToNextLesson" x-ref="next" :variant="$isCompleted ? 'primary' : 'outline'" icon-end="arrow-right" class="shrink-0" :label="__('lessons.next_lesson')"><span class="max-sm:sr-only">{{ __('lessons.next_lesson') }}</span></x-button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="menu" x-cloak x-transition.opacity class="fixed inset-0 z-overlay bg-overlay/70 backdrop-blur-sm lg:hidden" x-on:click="menu = false" aria-hidden="true"></div>

            <aside
                id="lv-curriculum"
                aria-label="{{ __('lessons.curriculum') }}"
                x-bind:inert="!desk && !menu"
                class="pb-safe flex flex-col overflow-hidden bg-surface-raised max-lg:fixed max-lg:inset-x-0 max-lg:bottom-0 max-lg:z-modal max-lg:max-h-[82dvh] max-lg:rounded-t-3xl max-lg:shadow-float max-lg:transition-transform max-lg:duration-base max-lg:ease-enter motion-reduce:max-lg:transition-none lg:card-surface lg:sticky lg:top-20 lg:max-h-[calc(100dvh-6rem)]"
                x-bind:class="menu ? 'max-lg:translate-y-0' : 'max-lg:translate-y-full'"
                x-on:keydown.escape="menu = false"
            >
                <div class="flex items-center gap-3 border-b border-subtle p-4">
                    <x-progress-ring :percent="$percent" :size="52" :stroke="6" :label="__('dash_learn_ui.lv_progress')" />
                    <div class="min-w-0 flex-1">
                        <h2 class="heading-4 text-strong">{{ __('lessons.curriculum') }}</h2>
                        <p class="truncate text-xs text-muted">{{ $course->title }}</p>
                    </div>
                    <button type="button" class="tap-target focus-ring rounded-xl text-muted lg:hidden" x-on:click="menu = false" aria-label="{{ __('dash_learn_ui.lv_close_curriculum') }}">
                        <x-icon name="x-mark" class="size-6" aria-hidden="true" />
                    </button>
                </div>

                <ol class="flex-1 space-y-1 overflow-y-auto overscroll-contain p-2">
                    @foreach ($lessons as $i => $item)
                        @php
                            $itemDone = in_array($item->id, $completedIds, true);
                            $current = $item->id === $lesson->id;
                            $state = $current ? 'lv_state_current' : ($itemDone ? 'lv_state_done' : 'lv_state_todo');
                        @endphp
                        <li>
                            <a
                                href="{{ $lessonUrl($item) }}"
                                wire:navigate
                                x-on:click="menu = false"
                                @if ($current) aria-current="step" @endif
                                class="focus-ring group flex min-h-12 items-start gap-3 rounded-xl px-3 py-2.5 text-sm transition duration-fast {{ $current ? 'bg-tint font-semibold text-strong ring-1 ring-brand/40' : 'text-body [@media(hover:hover)]:hover:bg-surface-sunken [@media(hover:hover)]:hover:text-strong' }}"
                            >
                                <span class="mt-0.5 flex size-6 shrink-0 items-center justify-center" title="{{ __('dash_learn_ui.'.$state) }}">
                                    @if ($itemDone)
                                        <x-icon name="check-circle" class="size-6 text-success" aria-hidden="true" />
                                    @elseif ($current)
                                        <x-icon name="play-circle" class="size-6 text-brand-primary" aria-hidden="true" />
                                    @else
                                        <span class="flex size-5 items-center justify-center rounded-full border border-strong text-[0.6875rem] font-bold tabular-nums text-muted">{{ $i + 1 }}</span>
                                    @endif
                                    <span class="sr-only">{{ __('dash_learn_ui.'.$state) }}</span>
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="line-clamp-2 break-words">{{ $item->title }}</span>
                                    @if ($item->duration_minutes)
                                        <span class="mt-0.5 block text-xs text-muted">{{ __('dash_learn_ui.lv_minutes', ['count' => $item->duration_minutes]) }}</span>
                                    @endif
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ol>
            </aside>
        </div>
    </div>
</div>
