<div>
    <x-slot name="seo">
        <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" />
        <meta name="robots" content="noindex, nofollow">
    </x-slot>

    @php
        $locale = app()->getLocale();
        $percent = (int) $enrollment->progress_percent;
        $moduleUrl = fn ($m) => route('dashboard.courses.study-module', ['locale' => $locale, 'course' => $course->slug, 'module' => $m->uuid]);
        $typeIcons = [
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
    @endphp

    <div class="mx-auto w-full max-w-7xl px-4 pt-4 md:px-6 md:pt-6">
        <x-breadcrumbs :items="[
            ['label' => __('dashboard.nav_my_courses'), 'url' => route('dashboard.courses.index', $locale)],
            ['label' => $course->title, 'url' => route('courses.show', ['locale' => $locale, 'slug' => $course->slug])],
            ['label' => $module->title],
        ]" />

        <div class="grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_21rem] xl:gap-8">
            <div class="min-w-0 space-y-5">
                <header class="space-y-3">
                    <h1 class="heading-2 break-words text-strong">{{ $module->title }}</h1>
                    <div class="flex items-center gap-3">
                        <div class="h-2 flex-1 overflow-hidden rounded-full bg-surface-sunken" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $percent }}">
                            <div class="h-full w-full origin-left rounded-full bg-gradient-to-r from-primary-500 to-secondary transition-transform duration-slow ease-enter rtl:origin-right rtl:bg-gradient-to-l motion-reduce:transition-none" style="transform: scaleX({{ $percent / 100 }})"></div>
                        </div>
                        <span class="shrink-0 text-sm font-bold tabular-nums text-strong">{{ $percent }}%</span>
                    </div>
                </header>

                <div class="space-y-4">
                    @foreach ($decoratedBlocks as $row)
                        @php
                            $block = $row['block'];
                            $progress = $row['progress'];
                            $state = $row['state'];
                            $content = $block->content ?? [];
                        @endphp
                        <article class="card-surface overflow-hidden {{ $state === 'locked' ? 'opacity-60' : '' }}" x-data="{ open: {{ $state !== 'locked' ? 'true' : 'false' }} }">
                            <button type="button" x-on:click="open = !open" @disabled($state === 'locked') class="focus-ring flex w-full items-center gap-3 p-4 text-start">
                                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-tint text-brand-primary">
                                    @if ($state === 'done')
                                        <x-icon name="check-circle" class="size-5 text-success" aria-hidden="true" />
                                    @elseif ($state === 'locked')
                                        <x-icon name="lock-closed" class="size-5" aria-hidden="true" />
                                    @else
                                        <x-icon :name="$typeIcons[$block->type] ?? 'document-text'" class="size-5" aria-hidden="true" />
                                    @endif
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-sm font-semibold text-strong">{{ $block->title }}</span>
                                    <span class="block text-xs text-muted">{{ __('course_learn_ui.type_'.$block->type) }}</span>
                                </span>
                                <x-icon name="chevron-down" class="size-5 shrink-0 text-muted transition duration-fast" x-bind:class="open ? 'rotate-180' : ''" aria-hidden="true" />
                            </button>

                            <div x-show="open" x-cloak x-collapse class="border-t border-subtle p-4 sm:p-6">
                                @if ($state === 'locked')
                                    <p class="text-sm text-muted">{{ __('course_learn_ui.locked_hint') }}</p>
                                @else
                                    @switch($block->type)
                                        @case('reading')
                                        @case('research_reading')
                                            <div class="prose-content max-w-none">{!! $content['body'] ?? '' !!}</div>
                                            @if ($state !== 'done')
                                                <x-button type="button" wire:click="markDone({{ $block->id }})" variant="primary" icon="check" class="mt-4">{{ __('course_learn_ui.mark_done') }}</x-button>
                                            @endif
                                        @break

                                        @case('case_study')
                                        @case('research_paper')
                                        @case('case_analysis')
                                            <div class="prose-content max-w-none">{!! $content['body'] ?? '' !!}</div>
                                            @if (!empty($content['links']))
                                                <ul class="mt-4 space-y-2">
                                                    @foreach ($content['links'] as $link)
                                                        <li class="text-sm">
                                                            <a href="{{ $link['url'] }}" target="_blank" rel="noopener" class="font-semibold text-brand-primary underline">{{ $link['url'] }}</a>
                                                            <span class="block text-xs text-muted">{{ $link['reference'] ?? '' }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                            @if ($state !== 'done')
                                                <x-button type="button" wire:click="markDone({{ $block->id }})" variant="primary" icon="check" class="mt-4">{{ __('course_learn_ui.mark_done') }}</x-button>
                                            @endif
                                        @break

                                        @case('practical_quiz')
                                        @case('graded_quiz')
                                            @php $score = $progress?->data['score'] ?? null; @endphp
                                            @if ($score !== null)
                                                <p class="mb-4 text-sm font-semibold text-strong">{{ __('course_learn_ui.quiz_score', ['score' => $score]) }}</p>
                                            @endif
                                            <div class="space-y-5">
                                                @foreach (($content['questions'] ?? []) as $qi => $question)
                                                    <div>
                                                        <p class="font-semibold text-strong">{{ $question['question'] }}</p>
                                                        <div class="mt-2 space-y-2">
                                                            @foreach (($question['options'] ?? []) as $oi => $option)
                                                                <label class="flex items-center gap-2 text-sm">
                                                                    <input type="radio" wire:model="quizAnswers.{{ $block->id }}.{{ $qi }}" value="{{ $oi }}" class="focus-ring">
                                                                    {{ $option['text'] }}
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                        @if ($score !== null && !empty($question['explanation']))
                                                            <p class="mt-1 text-xs text-muted">{{ $question['explanation'] }}</p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                            <x-button type="button" wire:click="submitQuiz({{ $block->id }})" variant="primary" icon="paper-airplane" class="mt-4">{{ __('course_learn_ui.submit_quiz') }}</x-button>
                                        @break

                                        @case('discussion')
                                            <p class="text-body">{{ $content['prompt'] ?? '' }}</p>
                                            <div class="mt-4 space-y-3">
                                                @foreach ($block->discussionReplies()->forStudent(auth()->id())->oldest()->get() as $reply)
                                                    <div class="rounded-xl border border-subtle p-3 text-sm {{ $reply->author_id === auth()->id() ? 'bg-tint' : 'bg-surface-sunken' }}">
                                                        <p class="font-semibold text-strong">{{ $reply->author_id === auth()->id() ? __('course_learn_ui.you') : __('course_learn_ui.instructor') }}</p>
                                                        <p class="mt-1 text-body">{{ $reply->body }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <form wire:submit="postDiscussionReply({{ $block->id }})" class="mt-4 space-y-2">
                                                <x-textarea name="discussion-reply-{{ $block->id }}" wire:model="discussionBody.{{ $block->id }}" rows="3" :placeholder="__('course_learn_ui.reply_placeholder')" />
                                                <x-button type="submit" variant="primary" icon="paper-airplane">{{ __('course_learn_ui.post_reply') }}</x-button>
                                            </form>
                                        @break

                                        @case('assignment')
                                            <div class="prose-content max-w-none">{!! $content['instructions'] ?? '' !!}</div>
                                            @if (!empty($content['due_date']))
                                                <p class="mt-2 text-xs text-muted">{{ __('course_learn_ui.due_date', ['date' => $content['due_date']]) }}</p>
                                            @endif
                                            @php $mark = $progress?->data['mark'] ?? 'not_submitted'; @endphp
                                            <p class="mt-3 text-sm font-semibold text-strong">{{ __('course_learn_ui.status_'.$mark) }}</p>
                                            <form wire:submit="submitAssignment({{ $block->id }})" class="mt-3 space-y-2">
                                                <input type="file" wire:model="assignmentUpload" class="block w-full text-sm">
                                                <x-button type="submit" variant="primary" icon="arrow-up-tray">{{ __('course_learn_ui.submit_assignment') }}</x-button>
                                            </form>
                                        @break

                                        @case('research_activity')
                                            <div class="prose-content max-w-none">{!! $content['prompt'] ?? '' !!}</div>
                                            @if (!empty($content['requires_submission']))
                                                <form wire:submit="submitAssignment({{ $block->id }})" class="mt-3 space-y-2">
                                                    <input type="file" wire:model="assignmentUpload" class="block w-full text-sm">
                                                    <x-button type="submit" variant="primary" icon="arrow-up-tray">{{ __('course_learn_ui.submit_assignment') }}</x-button>
                                                </form>
                                            @elseif ($state !== 'done')
                                                <x-button type="button" wire:click="markDone({{ $block->id }})" variant="primary" icon="check" class="mt-4">{{ __('course_learn_ui.mark_done') }}</x-button>
                                            @endif
                                        @break
                                    @endswitch
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <aside class="card-surface sticky top-20 hidden max-h-[calc(100dvh-6rem)] overflow-y-auto p-4 lg:block">
                <h2 class="heading-4 mb-3 text-strong">{{ __('course_learn_ui.modules') }}</h2>
                <ol class="space-y-1">
                    @foreach ($modules as $m)
                        <li>
                            <a href="{{ $moduleUrl($m) }}" wire:navigate class="focus-ring block rounded-xl px-3 py-2 text-sm {{ $m->id === $module->id ? 'bg-tint font-semibold text-strong' : 'text-body hover:bg-surface-sunken' }}">
                                {{ $m->title }}
                            </a>
                        </li>
                    @endforeach
                </ol>
            </aside>
        </div>
    </div>
</div>
