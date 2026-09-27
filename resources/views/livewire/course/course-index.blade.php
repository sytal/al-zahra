@php
$filtered = $audience !== '' || $level !== '' || $pricing !== '' || $search !== '';
$items = $courses->getCollection();
$featured = (! $filtered && $courses->currentPage() === 1 && $items->count() >= 3) ? $items->first() : null;
$rest = $featured ? $items->slice(1) : $items;
$audienceChips = collect(\App\Support\Enums\CourseAudience::cases())->map(fn ($c) => ['value' => $c->value, 'label' => __('enums.course_audience.'.$c->value)])->all();
$levelOptions = ['' => __('courses.all_levels')] + collect(\App\Support\Enums\CourseLevel::cases())->mapWithKeys(fn ($c) => [$c->value => __('enums.course_level.'.$c->value)])->all();
$target = 'audience,level,pricing,search,gotoPage,nextPage,previousPage,setPage';
@endphp

<div>
    <x-hero variant="banner" illustration="courses" :eyebrow="__('courses_ui.hero_eyebrow')" :title="__('courses.page_title')" :lead="__('courses.page_intro')">
    </x-hero>

    <section class="section-tight bg-section-light" data-results aria-label="{{ __('courses.page_title') }}">
        <div class="container-page">
            <x-filter-bar
                sticky
                chips-model="audience" :chips="$audienceChips" :selected="$audience" :chips-label="__('courses.filter_audience')" :all-label="__('courses.all_audiences')"
                search-model="search" :search="$search" :search-label="__('courses.search_label')" :placeholder="__('courses_ui.search_placeholder')"
                :count="$courses->total()" :clear-models="['level', 'pricing']"
            >
                <x-select name="level" :label="__('courses.filter_level')" hide-label :options="$levelOptions" :value="$level" wire:model.live="level" class="w-full sm:w-48" />
                <x-segmented-control name="pricing" :label="__('courses_ui.pricing_label')" :options="['' => __('courses.all_pricing'), 'free' => __('common.free'), 'paid' => __('common.paid')]" :value="$pricing" wire:model.live="pricing" class="sm:self-center" />
            </x-filter-bar>

            <div class="mt-8" wire:loading.class="opacity-60" wire:target="{{ $target }}">
                @if ($courses->isEmpty())
                    <x-empty-state icon="academic-cap" illustration="courses" :title="__('courses.empty_title')" :message="__('courses.empty_message')">
                        @if ($filtered)
                            <x-slot name="action">
                                <x-button variant="outline" icon="x-mark" x-on:click="$wire.set('audience', ''); $wire.set('level', ''); $wire.set('pricing', ''); $wire.set('search', '')">{{ __('courses_ui.clear') }}</x-button>
                            </x-slot>
                        @endif
                    </x-empty-state>
                @else
                    @if ($featured)
                        @php
                        $fImg = $featured->getFirstMediaUrl('cover_image', 'hero');
                        $fallback = asset('images/course-placeholder.svg');
                        $fUrl = route('courses.show', ['locale' => app()->getLocale(), 'slug' => $featured->slug]);
                        @endphp
                        <article class="spotlight gradient-border card-surface group relative mb-8 grid min-w-0 overflow-hidden !rounded-panel md:grid-cols-2" x-data="spotlight">
                            <div class="img-zoom relative aspect-[3/2] min-w-0 bg-surface-sunken md:aspect-auto md:min-h-[20rem]">
                                <img src="{{ $fImg ?: $fallback }}" alt="{{ $featured->title }}" width="1200" height="630" decoding="async" onerror="this.onerror=null;this.src='{{ $fallback }}'" class="absolute inset-0 size-full object-cover dark:brightness-90">
                                <span class="absolute start-3 top-3"><x-badge color="accent" variant="solid" icon="sparkles" :text="__('courses_ui.featured')" /></span>
                            </div>
                            <div class="relative flex min-w-0 flex-col justify-center gap-4 p-6 sm:p-8 lg:p-10">
                                <span class="star-mark pointer-events-none absolute -end-4 -top-4 text-8xl opacity-15" aria-hidden="true"></span>
                                <div class="flex flex-wrap items-center gap-3">
                                    <x-level-meter :level="$featured->level" />
                                    <x-badge :color="$featured->is_free ? 'success' : 'warning'" :text="$featured->is_free ? __('common.free') : ($featured->formattedPrice() ?? __('common.paid'))" />
                                </div>
                                <h2 class="heading-2 break-words"><a href="{{ $fUrl }}" wire:navigate class="focus-ring after:absolute after:inset-0">{{ $featured->title }}</a></h2>
                                <p class="line-clamp-3 text-body">{{ $featured->short_description }}</p>
                                <ul class="flex flex-wrap gap-x-5 gap-y-2 text-sm text-muted">
                                    @if ($featured->lessons_count)<li class="inline-flex items-center gap-1.5"><x-icon name="play-circle" class="size-4" aria-hidden="true" />{{ trans_choice('kit_sections.lessons', $featured->lessons_count, ['count' => $featured->lessons_count]) }}</li>@endif
                                    @if ($featured->estimated_duration_hours)<li class="inline-flex items-center gap-1.5"><x-icon name="clock" class="size-4" aria-hidden="true" />{{ trans_choice('kit_sections.hours', $featured->estimated_duration_hours, ['count' => $featured->estimated_duration_hours]) }}</li>@endif
                                    <li class="inline-flex items-center gap-1.5"><x-icon name="users" class="size-4" aria-hidden="true" />{{ trans_choice('kit_sections.learners', (int) $featured->enrolled_count, ['count' => number_format((int) $featured->enrolled_count)]) }}</li>
                                </ul>
                                <p class="inline-flex items-center gap-2 font-semibold text-brand-primary"><span class="link-underline">{{ __('courses_ui.start_course') }}</span><x-icon name="arrow-right" class="size-5 rtl:-scale-x-100" aria-hidden="true" /></p>
                            </div>
                        </article>
                    @endif

                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($rest as $course)
                            <x-course-card :course="$course" as="h2" wire:key="course-{{ $course->id }}" />
                        @endforeach
                    </div>

                    <div class="mt-10">
                        <x-pagination :paginator="$courses" />
                    </div>
                @endif
            </div>

            <div class="mt-8 hidden gap-6 sm:grid-cols-2 lg:grid-cols-3" wire:loading.class.remove="hidden" wire:loading.class="grid" wire:target="{{ $target }}" aria-hidden="true">
                @foreach (range(1, 3) as $i)
                    <x-course-card skeleton />
                @endforeach
            </div>
        </div>
    </section>
</div>
