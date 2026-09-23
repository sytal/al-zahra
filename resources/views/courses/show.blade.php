<x-layouts.public>
    <x-slot name="seo">
        <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" />
    </x-slot>

    <x-detail-layout :title="$course->title">
        <x-slot name="meta">
            <x-breadcrumbs :items="[
                ['label' => __('nav.courses'), 'url' => route('courses.index', app()->getLocale())],
                ['label' => $course->title],
            ]" />
            <x-badge color="brand" :text="ucfirst($course->level->value)" />
            <x-badge color="neutral" :text="ucfirst($course->audience->value)" />
            <x-badge :color="$course->is_free ? 'success' : 'warning'" :text="$course->is_free ? __('courses.free') : __('courses.paid')" />
        </x-slot>

        @if ($course->hasMedia('cover_image'))
            <img src="{{ $course->getFirstMediaUrl('cover_image', 'hero') }}" alt="{{ $course->title }}" class="mb-8 w-full rounded-xl">
        @endif

        <p class="text-lg text-ink/80">{{ $course->short_description }}</p>

        {!! $course->full_description !!}

        @if (!empty($course->learning_outcomes))
            <h2 class="mt-8 text-xl font-semibold text-ink">{{ __('courses.what_youll_learn') }}</h2>
            <ul class="mt-3 space-y-2">
                @foreach ($course->learning_outcomes as $outcome)
                    <li class="flex items-start gap-2 text-ink/80">
                        <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0 text-success" />
                        {{ $outcome }}
                    </li>
                @endforeach
            </ul>
        @endif

        @if ($course->lessons->isNotEmpty())
            <h2 class="mt-8 text-xl font-semibold text-ink">{{ __('courses.curriculum') }}</h2>
            <div class="mt-3">
                <x-accordion :items="$course->lessons->map(fn ($lesson) => [
                    'title' => $lesson->title . ($lesson->duration_minutes ? ' · ' . $lesson->duration_minutes . ' min' : ''),
                    'content' => ($lesson->is_preview || $isEnrolled)
                        ? ($lesson->body ?? '')
                        : __('courses.login_to_enroll'),
                ])->all()" />
            </div>
        @endif

        <h2 class="mt-8 text-xl font-semibold text-ink">{{ __('courses.instructor') }}</h2>
        <div class="mt-3">
            @if ($course->instructor && $course->instructor->hasRole('director'))
                <x-director-profile :compact="true" />
            @elseif ($course->instructor)
                <x-instructor-card :instructor="$course->instructor" />
            @endif
        </div>

        <div class="mt-8">
            @auth
                @if ($isEnrolled)
                    <x-button href="#" variant="primary">{{ __('courses.continue_learning') }}</x-button>
                @else
                    <form method="POST" action="{{ route('courses.enroll', ['locale' => app()->getLocale(), 'slug' => $course->slug]) }}">
                        @csrf
                        <x-button type="submit" variant="primary">{{ __('courses.enroll_now') }}</x-button>
                    </form>
                @endif
            @else
                <x-button :href="route('login', app()->getLocale())" variant="primary">{{ __('courses.login_to_enroll') }}</x-button>
            @endauth
        </div>

        <x-slot name="related">
            @if ($related->isNotEmpty())
                <h2 class="mb-4 text-xl font-semibold text-ink">{{ __('courses.related_title') }}</h2>
                <div class="grid gap-6 sm:grid-cols-3">
                    @foreach ($related as $item)
                        <x-card hoverable>
                            <a href="{{ route('courses.show', ['locale' => app()->getLocale(), 'slug' => $item->slug]) }}" wire:navigate>
                                <h3 class="font-medium text-ink">{{ $item->title }}</h3>
                            </a>
                        </x-card>
                    @endforeach
                </div>
            @endif
        </x-slot>
    </x-detail-layout>
</x-layouts.public>
