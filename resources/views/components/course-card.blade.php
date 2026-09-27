{{--
Progress-oriented course card. Props: course (Course model or array with title, short_description, slug, level (enum/string), audience (enum/string), is_free, enrolled_count, lessons_count, estimated_duration_hours, image),
progress (0-100 or null; when set shows a progress bar and a Continue action), variant default|compact, url, as h2|h3, skeleton bool.
--}}
@props(['course' => null, 'progress' => null, 'variant' => 'default', 'url' => null, 'as' => 'h3', 'skeleton' => false])

@if ($skeleton)
    <div {{ $attributes->merge(['class' => 'card-surface flex h-full flex-col overflow-hidden']) }} aria-hidden="true">
        <div class="skeleton aspect-[3/2] w-full !rounded-none"></div>
        <div class="flex flex-col gap-3 p-5">
            <div class="skeleton h-4 w-1/2"></div>
            <div class="skeleton h-5 w-full"></div>
            <div class="skeleton h-3 w-full"></div>
            <div class="skeleton h-3 w-3/4"></div>
            <div class="skeleton mt-2 h-2 w-full"></div>
        </div>
    </div>
@else
    @php
    $c = $course;
    $title = data_get($c, 'title', '');
    $desc = data_get($c, 'short_description');
    $level = data_get($c, 'level');
    $audience = data_get($c, 'audience');
    $audienceKey = $audience instanceof \BackedEnum ? $audience->value : $audience;
    $free = (bool) data_get($c, 'is_free', false);
    $priceLabel = $free ? __('common.free') : (($c instanceof \App\Modules\Course\Models\Course ? $c->formattedPrice() : null) ?? __('common.paid'));
    $learners = data_get($c, 'enrolled_count');
    $lessons = data_get($c, 'lessons_count');
    $hours = data_get($c, 'estimated_duration_hours');
    $fallback = asset('images/course-placeholder.svg');
    $img = data_get($c, 'image') ?: (is_object($c) && method_exists($c, 'getFirstMediaUrl') ? $c->getFirstMediaUrl('cover_image', 'card') : null);
    $link = $url ?? data_get($c, 'url') ?? (is_object($c) && isset($c->slug) ? route('courses.show', ['locale' => app()->getLocale(), 'slug' => $c->slug]) : '#');
    $Tag = $as;
    $hasProgress = $progress !== null;
    @endphp

    @if ($variant === 'compact')
        <article {{ $attributes->merge(['class' => 'card-surface card-hover group relative flex min-w-0 items-center gap-4 p-3']) }}>
            <div class="img-zoom relative aspect-[3/2] w-24 shrink-0 overflow-hidden rounded-xl bg-surface-sunken sm:w-32">
                <img src="{{ $img ?: $fallback }}" alt="{{ $title }}" width="300" height="200" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='{{ $fallback }}'" class="absolute inset-0 size-full object-cover dark:brightness-90">
            </div>
            <div class="min-w-0 flex-1">
                @if ($level)<x-level-meter :level="$level" class="mb-1" />@endif
                <{{ $Tag }} class="line-clamp-2 break-words font-display text-base font-semibold leading-snug text-strong"><a href="{{ $link }}" wire:navigate class="focus-ring after:absolute after:inset-0">{{ $title }}</a></{{ $Tag }}>
                @if ($hasProgress)<x-progress-bar :percent="$progress" size="sm" class="mt-2" />@endif
            </div>
        </article>
    @else
        <article {{ $attributes->merge(['class' => 'card-surface card-hover group relative flex h-full min-w-0 flex-col overflow-hidden']) }}>
            <div class="img-zoom relative aspect-[3/2] w-full overflow-hidden bg-surface-sunken">
                <img src="{{ $img ?: $fallback }}" alt="{{ $title }}" width="600" height="400" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='{{ $fallback }}'" class="absolute inset-0 size-full object-cover dark:brightness-90">
                @if ($img)<span class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/35 to-transparent" aria-hidden="true"></span>@endif
                <span class="absolute end-3 top-3"><x-badge :color="$free ? 'success' : 'warning'" variant="solid" size="sm" :text="$priceLabel" /></span>
                @if ($level)<span class="glass absolute bottom-3 start-3 !rounded-full px-3 py-1.5"><x-level-meter :level="$level" /></span>@endif
            </div>
            <div class="flex min-w-0 flex-1 flex-col gap-3 p-5">
                @if ($audienceKey)<p class="flex items-center gap-1.5 text-xs font-semibold text-secondary-text"><span class="star-mark text-[0.6rem]" aria-hidden="true"></span><span class="truncate">{{ __('enums.course_audience.' . $audienceKey) }}</span></p>@endif
                <{{ $Tag }} class="heading-4 line-clamp-3 break-words"><a href="{{ $link }}" wire:navigate class="focus-ring after:absolute after:inset-0">{{ $title }}</a></{{ $Tag }}>
                @if ($desc)<p class="line-clamp-2 text-sm text-body">{{ $desc }}</p>@endif
                <ul class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-muted">
                    @if ($lessons)<li class="inline-flex items-center gap-1"><x-icon name="play-circle" class="size-4" aria-hidden="true" />{{ trans_choice('kit_sections.lessons', $lessons, ['count' => $lessons]) }}</li>@endif
                    @if ($hours)<li class="inline-flex items-center gap-1"><x-icon name="clock" class="size-4" aria-hidden="true" />{{ trans_choice('kit_sections.hours', $hours, ['count' => $hours]) }}</li>@endif
                    @if ($learners !== null)<li class="inline-flex items-center gap-1"><x-icon name="users" class="size-4" aria-hidden="true" />{{ trans_choice('kit_sections.learners', $learners, ['count' => number_format((int) $learners)]) }}</li>@endif
                </ul>
                <div class="mt-auto pt-2">
                    @if ($hasProgress)
                        <x-progress-bar :percent="$progress" :label="__('kit_sections.progress')" />
                        <p class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-primary">{{ (int) $progress >= 100 ? __('kit_sections.review_course') : __('kit_sections.continue_learning') }}<x-icon name="arrow-right" class="size-4 rtl:-scale-x-100" aria-hidden="true" /></p>
                    @else
                        <p class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand-primary"><span class="link-underline">{{ __('kit_sections.view_course') }}</span><x-icon name="arrow-right" class="size-4 transition-transform duration-base ease-enter rtl:-scale-x-100 [@media(hover:hover)]:group-hover:translate-x-1 rtl:[@media(hover:hover)]:group-hover:-translate-x-1" aria-hidden="true" /></p>
                    @endif
                </div>
            </div>
        </article>
    @endif
@endif
