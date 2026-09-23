<div class="mx-auto max-w-6xl px-4 py-10" data-aos="fade-up">
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-ink">{{ __('courses.page_title') }}</h1>
        <p class="mt-2 text-ink/60">{{ __('courses.page_intro') }}</p>
    </header>

    <div class="mb-8 flex flex-wrap gap-4">
        <x-select name="audience" :label="__('courses.filter_audience')" wire:model.live="audience" class="w-44">
            <option value="">{{ __('courses.all_audiences') }}</option>
            @foreach (\App\Support\Enums\CourseAudience::cases() as $case)
                <option value="{{ $case->value }}">{{ ucfirst($case->value) }}</option>
            @endforeach
        </x-select>

        <x-select name="level" :label="__('courses.filter_level')" wire:model.live="level" class="w-44">
            <option value="">{{ __('courses.all_levels') }}</option>
            @foreach (\App\Support\Enums\CourseLevel::cases() as $case)
                <option value="{{ $case->value }}">{{ ucfirst($case->value) }}</option>
            @endforeach
        </x-select>

        <x-select name="pricing" :label="__('courses.filter_pricing')" wire:model.live="pricing" class="w-36">
            <option value="">{{ __('courses.all_pricing') }}</option>
            <option value="free">{{ __('common.free') }}</option>
            <option value="paid">{{ __('common.paid') }}</option>
        </x-select>

        <x-input name="search" :label="__('courses.search_label')" wire:model.live.debounce.400ms="search" class="flex-1" />
    </div>

    @if ($courses->isEmpty())
        <x-empty-state icon="academic-cap" :title="__('courses.empty_title')" :message="__('courses.empty_message')" />
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($courses as $course)
                <x-card hoverable class="flex flex-col overflow-hidden !p-0">
                    <a href="{{ route('courses.show', ['locale' => app()->getLocale(), 'slug' => $course->slug]) }}" wire:navigate>
                        <img
                            src="{{ $course->getFirstMediaUrl('cover_image', 'card') ?: asset('images/course-placeholder.svg') }}"
                            alt="{{ $course->title }}"
                            class="aspect-video w-full object-cover"
                        >
                        <div class="p-5">
                            <div class="mb-2 flex flex-wrap gap-1.5">
                                <x-badge color="brand" :text="ucfirst($course->level->value)" />
                                <x-badge color="neutral" :text="ucfirst($course->audience->value)" />
                                <x-badge :color="$course->is_free ? 'success' : 'warning'" :text="$course->is_free ? __('common.free') : __('common.paid')" />
                            </div>
                            <h2 class="font-semibold text-ink">{{ $course->title }}</h2>
                            <p class="mt-1 text-sm text-ink/60">{{ $course->short_description }}</p>
                            <p class="mt-3 text-xs text-ink/40">{{ $course->enrolled_count }} {{ __('courses.students_enrolled') }}</p>
                        </div>
                    </a>
                </x-card>
            @endforeach
        </div>

        <x-pagination :paginator="$courses" />
    @endif
</div>
