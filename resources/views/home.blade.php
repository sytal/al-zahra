@php
$loc = app()->getLocale();
$consult = route('consultation.show', $loc);
$creds = collect($director?->credentials ?? [])->filter()->values();
$interests = collect($director?->research_interests ?? [])->filter()->values();
$photo = $director?->getFirstMediaUrl('profile_photo');
$faq = collect(range(1, 6))->map(fn ($i) => ['q' => __("home_ui.faq_{$i}_q"), 'a' => __("home_ui.faq_{$i}_a")])->all();
$steps = collect(range(1, 4))->map(fn ($i) => ['title' => __("home_ui.how_{$i}_title"), 'text' => __("home_ui.how_{$i}_text"), 'icon' => ['pencil-square', 'eye', 'chat-bubble-left-right', 'clipboard-document-check'][$i - 1]])->all();
$statRows = [
    ['to' => $stats['articles'], 'label' => __('home_ui.stat_articles'), 'icon' => 'document-text'],
    ['to' => $stats['courses'], 'label' => __('home_ui.stat_courses'), 'icon' => 'academic-cap'],
    ['to' => $stats['papers'], 'label' => __('home_ui.stat_papers'), 'icon' => 'beaker'],
    ['to' => $stats['learners'], 'label' => __('home_ui.stat_learners'), 'icon' => 'users'],
];
@endphp

<x-layouts.public>
    <x-slot name="seo">
        <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" />
    </x-slot>

    <x-hero variant="home" :title="$tagline" :eyebrow="__('home_ui.eyebrow')" :lead="__('home.hero_subtext')">
        <x-slot name="actions">
            <x-button :href="$consult" size="lg" class="magnetic" x-data="magnetic">{{ __('home_ui.cta_book') }}</x-button>
            <x-button :href="route('courses.index', $loc)" variant="outline" size="lg">{{ __('home_ui.cta_courses') }}</x-button>
        </x-slot>
        @if ($creds->isNotEmpty())
            <x-slot name="chips">
                @foreach ($creds->take(4) as $credential)
                    <li><x-tag-pill :text="$credential" icon="check-badge" /></li>
                @endforeach
            </x-slot>
        @endif
        <x-slot name="floating">
            <div class="glass flex items-center gap-3 p-4">
                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-tint text-brand-primary"><x-icon name="document-text" class="size-5" aria-hidden="true" /></span>
                <p class="min-w-0"><span class="block font-display text-2xl font-bold leading-none text-strong tabular-nums" dir="ltr">{{ number_format($stats['articles']) }}</span><span class="block truncate text-xs text-body">{{ __('home_ui.stat_articles') }}</span></p>
            </div>
            <div class="glass flex items-center gap-3 p-4">
                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-tint text-brand-primary"><x-icon name="beaker" class="size-5" aria-hidden="true" /></span>
                <p class="min-w-0"><span class="block font-display text-2xl font-bold leading-none text-strong tabular-nums" dir="ltr">{{ number_format($stats['papers']) }}</span><span class="block truncate text-xs text-body">{{ __('home_ui.stat_papers') }}</span></p>
            </div>
        </x-slot>
    </x-hero>

    {{-- Trust counters --}}
    <x-section variant="light" tight class="lg:pt-20" :aria-label="__('home_ui.stats_label')">
        <dl class="grid grid-cols-2 gap-x-6 gap-y-10 text-center lg:grid-cols-4">
            @foreach ($statRows as $row)
                <div class="relative flex min-w-0 flex-col items-center px-2 {{ ! $loop->last ? 'lg:after:absolute lg:after:end-0 lg:after:top-1/4 lg:after:h-1/2 lg:after:w-px lg:after:bg-[var(--border-strong)]' : '' }}">
                    <x-icon :name="$row['icon']" class="mb-2 size-6 text-secondary-text" aria-hidden="true" />
                    <dd dir="ltr" class="order-1 font-display text-4xl font-bold leading-none text-brand-primary tabular-nums sm:text-5xl lg:text-6xl" x-data="counter({ to: {{ (int) $row['to'] }}, locale: '{{ $loc === 'ur-roman' ? 'en' : $loc }}' })" x-text="display">{{ number_format($row['to']) }}</dd>
                    <dt class="order-2 mt-2 text-balance text-sm font-medium text-body sm:text-base">{{ $row['label'] }}</dt>
                </div>
            @endforeach
        </dl>
    </x-section>

    {{-- Director intro --}}
    <x-section variant="tinted" thread>
        @if ($director)
            <div class="grid items-center gap-10 lg:grid-cols-12 lg:gap-14">
                <div class="relative isolate mx-auto w-full max-w-xs lg:col-span-4 lg:max-w-none">
                    <span class="star-mark pointer-events-none absolute -start-8 -top-8 -z-10 text-[12rem] opacity-20" aria-hidden="true"></span>
                    <span class="absolute inset-0 -z-10 translate-x-3 translate-y-3 rounded-panel border-2 border-brand-secondary rtl:-translate-x-3" aria-hidden="true"></span>
                    <div class="relative aspect-[4/5] overflow-hidden rounded-panel bg-tint shadow-float">
                        <img src="{{ $photo ?: asset('images/director-placeholder.svg') }}" alt="{{ $director->full_name }}" width="400" height="500" loading="lazy" decoding="async" class="size-full object-cover">
                    </div>
                </div>
                <div class="min-w-0 lg:col-span-8" data-aos="fade-up">
                    <p class="eyebrow mb-3">{{ __('home_ui.director_eyebrow') }}</p>
                    <h2 class="heading-2 break-words">{{ __('home_ui.director_title') }}</h2>
                    <p class="mt-5 text-xl font-semibold text-strong">{{ $director->full_name }}</p>
                    <p class="font-semibold text-secondary-text">{{ $director->professional_title }}</p>
                    @if ($director->bio_short)<p class="lead mt-4 max-w-2xl">{{ $director->bio_short }}</p>@else<p class="lead mt-4 max-w-2xl">{{ __('home_ui.director_lead') }}</p>@endif
                    @if ($creds->isNotEmpty())
                        <ul class="star-bullet mt-5 space-y-1.5 text-body">
                            @foreach ($creds as $c)<li class="break-words">{{ $c }}</li>@endforeach
                        </ul>
                    @endif
                    @if ($interests->isNotEmpty())
                        <p class="mt-6 text-sm font-semibold text-strong">{{ __('home_ui.director_interests') }}</p>
                        <ul class="mt-2 flex flex-wrap gap-2">
                            @foreach ($interests as $i)<li><x-tag-pill :text="$i" /></li>@endforeach
                        </ul>
                    @endif
                    <x-button :href="route('about', $loc)" variant="outline" class="mt-8">{{ __('home_ui.director_more') }}</x-button>
                </div>
            </div>
        @else
            <x-empty-state icon="user" :title="__('about_ui.empty_title')" :message="__('home_ui.director_empty')" />
        @endif
    </x-section>

    {{-- Services bento --}}
    <x-section variant="light">
        <x-section-heading align="center" :eyebrow="__('home_ui.services_eyebrow')" :title="__('home_ui.services_title')" :lead="__('home_ui.services_lead')" />
        <div class="grid gap-5 lg:grid-cols-3 lg:items-stretch" data-aos="fade-up">
            <x-service-card icon="chat-bubble-left-ellipsis" :title="__('home_ui.svc_free_title')" :text="__('home_ui.svc_free_text')" :cta="__('home_ui.svc_free_cta')" :url="$consult" />
            <x-service-card icon="calendar-days" featured :title="__('home_ui.svc_book_title')" :text="__('home_ui.svc_book_text')" :cta="__('home_ui.svc_book_cta')" :url="$consult" />
            <x-service-card icon="academic-cap" :title="__('home_ui.svc_learn_title')" :text="__('home_ui.svc_learn_text')" :cta="__('home_ui.svc_learn_cta')" :url="route('courses.index', $loc)" />
        </div>
    </x-section>

    {{-- Featured courses --}}
    <x-section variant="sunken">
        <x-section-heading :title="__('home_ui.courses_title')" :lead="__('home_ui.courses_lead')" :url="route('courses.index', $loc)" :linkLabel="__('home_ui.courses_all')" />
        @if ($featuredCourses->isEmpty())
            <x-empty-state illustration="courses" :title="__('home_ui.courses_empty')" :message="__('home_ui.courses_empty_text')" />
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 stagger">
                @foreach ($featuredCourses as $course)
                    <x-course-card :course="$course" />
                @endforeach
            </div>
        @endif
    </x-section>

    {{-- Latest articles --}}
    <x-section variant="light">
        <x-section-heading :title="__('home_ui.articles_title')" :lead="__('home_ui.articles_lead')" :url="route('articles.index', $loc)" :linkLabel="__('home_ui.articles_all')" />
        @php $arts = collect($latestArticles->items()); @endphp
        @if ($arts->isEmpty())
            <x-empty-state illustration="articles" :title="__('home_ui.articles_empty')" :message="__('home_ui.articles_empty_text')" />
        @else
            <div class="grid gap-6 lg:grid-cols-5">
                <div class="lg:col-span-3">
                    <x-article-card :article="$arts->first()" variant="default" class="!h-full" />
                </div>
                @if ($arts->count() > 1)
                    <div class="flex flex-col gap-3 lg:col-span-2">
                        @foreach ($arts->slice(1) as $a)
                            <x-article-card :article="$a" variant="compact" class="card-surface" />
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </x-section>

    {{-- Research + marquee --}}
    <x-section variant="pattern">
        <x-section-heading :title="__('home_ui.research_title')" :lead="__('home_ui.research_lead')" :url="route('research.index', $loc)" :linkLabel="__('home_ui.research_all')" />
        @if ($latestResearch->isEmpty())
            <x-empty-state illustration="research" :title="__('home_ui.research_empty')" :message="__('home_ui.research_empty_text')" />
        @else
            <div class="grid gap-6 md:grid-cols-2">
                @foreach ($latestResearch as $paper)
                    <x-research-card :paper="$paper" />
                @endforeach
            </div>
            @if (count($publications))
                <p class="eyebrow mb-4 mt-12">{{ __('home_ui.publications_label') }}</p>
                <x-publication-marquee :items="$publications" :label="__('home_ui.publications_label')" />
            @endif
        @endif
    </x-section>

    {{-- How a consultation works --}}
    <x-section variant="tinted">
        <x-section-heading align="center" :title="__('home_ui.how_title')" :lead="__('home_ui.how_lead')" />
        <x-steps :items="$steps" />
        <div class="mt-10 text-center">
            <x-button :href="$consult" size="lg">{{ __('home_ui.cta_book') }}</x-button>
        </div>
    </x-section>

    {{-- Testimonials --}}
    @if ($testimonials->isNotEmpty())
        <x-section variant="light">
            <x-section-heading align="center" :title="__('home_ui.testimonials_title')" />
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($testimonials as $t)
                    <x-testimonial-card :quote="$t['quote']" :name="$t['name']" :role="$t['role']" :demo="$t['demo']" />
                @endforeach
            </div>
            @if ($testimonials->contains('demo', true))
                <p class="mt-6 text-center text-sm text-muted">{{ __('home_ui.testimonials_note') }}</p>
            @endif
        </x-section>
    @endif

    {{-- FAQ --}}
    <x-section variant="sunken">
        <x-section-heading align="center" :eyebrow="__('home_ui.faq_eyebrow')" :title="__('home_ui.faq_title')" :lead="__('home_ui.faq_lead')" />
        <x-faq :items="$faq" :open="0" />
    </x-section>

    {{-- CTA + newsletter --}}
    <x-section variant="light">
        <x-cta-band :title="__('home_ui.cta_title')" :lead="__('home_ui.cta_lead')"
            :primaryLabel="__('home_ui.cta_primary')" :primaryUrl="$consult" :secondaryLabel="__('home_ui.cta_secondary')" :secondaryUrl="$consult" />
        <div class="mx-auto mt-14 max-w-xl text-center">
            <x-star-divider class="mb-6" />
            <h2 class="heading-3">{{ __('home_ui.newsletter_title') }}</h2>
            <p class="mt-2 text-body">{{ __('home_ui.newsletter_lead') }}</p>
            <x-newsletter-form class="mt-6 text-start" />
        </div>
    </x-section>
</x-layouts.public>
