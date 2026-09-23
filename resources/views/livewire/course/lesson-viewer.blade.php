<div>
    <x-slot name="seo">
        <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" />
        <meta name="robots" content="noindex, nofollow">
    </x-slot>

    <div class="mx-auto max-w-6xl px-4 py-8">
        <x-breadcrumbs :items="[
            ['label' => __('dashboard.nav_my_courses'), 'url' => route('dashboard.courses.index', app()->getLocale())],
            ['label' => $course->title, 'url' => route('courses.show', ['locale' => app()->getLocale(), 'slug' => $course->slug])],
            ['label' => $lesson->title],
        ]" />

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <x-card>
                    <h1 class="text-xl font-bold text-ink">{{ $lesson->title }}</h1>

                    @if ($lesson->content_type->value === 'video' && $lesson->video_url)
                        <div class="mt-4 aspect-video overflow-hidden rounded-lg bg-ink/5">
                            <iframe
                                src="{{ $lesson->video_url }}"
                                class="size-full"
                                allowfullscreen
                                title="{{ $lesson->title }}"
                            ></iframe>
                        </div>
                    @endif

                    @if ($lesson->body)
                        <div class="prose prose-sm mt-4 max-w-none text-ink/80">
                            {!! $lesson->body !!}
                        </div>
                    @endif

                    @if ($lesson->getMedia('lesson_attachments')->isNotEmpty())
                        <div class="mt-6">
                            <h2 class="text-sm font-semibold text-ink">{{ __('lessons.attachments') }}</h2>
                            <ul class="mt-2 space-y-2">
                                @foreach ($lesson->getMedia('lesson_attachments') as $media)
                                    <li>
                                        <a href="{{ $media->getUrl() }}" target="_blank" rel="noopener" class="flex items-center gap-2 text-sm text-brand-primary hover:underline">
                                            <x-icon name="paper-clip" class="size-4" />
                                            {{ $media->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </x-card>

                <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        @if ($isCompleted)
                            <x-badge color="success" :text="__('lessons.completed')" />
                        @else
                            <x-button wire:click="markComplete" variant="primary" size="sm" icon="check">
                                {{ __('lessons.mark_complete') }}
                            </x-button>
                        @endif
                    </div>

                    @if ($courseJustCompleted)
                        <x-badge color="brand" :text="__('lessons.course_complete_title')" />
                    @elseif ($hasNext)
                        <x-button wire:click="goToNextLesson" variant="outline" size="sm" icon="arrow-right">
                            {{ __('lessons.next_lesson') }}
                        </x-button>
                    @endif
                </div>

                @if ($courseJustCompleted)
                    <x-card class="mt-4 text-center">
                        <x-icon name="trophy" class="mx-auto size-10 text-brand-secondary" />
                        <p class="mt-2 font-medium text-ink">{{ __('lessons.course_complete_title') }}</p>
                        <p class="mt-1 text-sm text-ink/60">{{ __('lessons.course_complete_message') }}</p>
                        <div class="mt-4">
                            <x-button :href="route('dashboard.courses.index', app()->getLocale())" variant="primary" size="sm">
                                {{ __('lessons.back_to_my_courses') }}
                            </x-button>
                        </div>
                    </x-card>
                @endif
            </div>

            <div>
                <x-card padding="p-3">
                    <h2 class="px-2 py-1 text-sm font-semibold text-ink">{{ __('lessons.curriculum') }}</h2>
                    <ul class="mt-1 space-y-1">
                        @foreach ($lessons as $item)
                            @php $done = in_array($item->id, $completedIds, true); @endphp
                            <li>
                                <a
                                    href="{{ route('dashboard.courses.lesson', ['locale' => app()->getLocale(), 'course' => $course->slug, 'lesson' => $item->uuid]) }}"
                                    wire:navigate
                                    class="flex items-center gap-2 rounded-lg px-2 py-2 text-sm transition duration-200 ease-in-out {{ $item->id === $lesson->id ? 'bg-brand-primary/10 text-brand-primary font-medium' : 'text-ink/70 hover:bg-surface hover:text-ink' }}"
                                >
                                    <x-icon :name="$done ? 'check-circle' : 'play-circle'" class="size-4 shrink-0 {{ $done ? 'text-success' : 'text-ink/30' }}" />
                                    <span class="truncate">{{ $item->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </x-card>
            </div>
        </div>
    </div>
</div>
