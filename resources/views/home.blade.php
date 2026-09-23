@php
$tagline = \App\Modules\Setting\Models\Setting::where('key', 'site_tagline')->value('value');
$tagline = is_array($tagline) ? ($tagline[app()->getLocale()] ?? $tagline['en'] ?? '') : $tagline;
@endphp

<x-layouts.public>
    <x-slot name="seo">
        <x-seo :title="config('app.name')" :description="$tagline" />
    </x-slot>

    <!-- 1. Hero -->
    <section class="bg-gradient-to-br from-brand-primary to-brand-primary/70 px-4 py-20 text-white" data-aos="fade-up">
        <div class="mx-auto max-w-3xl text-center">
            <h1 class="text-4xl font-bold md:text-5xl">{{ $tagline }}</h1>
            <p class="mt-4 text-lg text-white/90">{{ __('home.hero_subtext') }}</p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <x-button :href="route('articles.index', app()->getLocale())" variant="secondary" size="lg">{{ __('home.explore_articles') }}</x-button>
                <x-button :href="route('courses.index', app()->getLocale())" variant="outline" size="lg" class="!border-white !text-white hover:!bg-white/10">{{ __('home.browse_courses') }}</x-button>
            </div>
        </div>
    </section>

    <!-- 2. Director intro strip -->
    <section class="mx-auto max-w-6xl px-4 py-16" data-aos="fade-up">
        <x-director-profile :compact="true" />
    </section>

    <!-- 3. Explore: latest articles -->
    <section class="bg-surface px-4 py-16" data-aos="fade-up">
        <div class="mx-auto max-w-6xl">
            <div class="mb-8 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-ink">{{ __('home.explore_title') }}</h2>
                <a href="{{ route('articles.index', app()->getLocale()) }}" wire:navigate class="text-sm text-brand-primary hover:underline">{{ __('home.view_all') }} &rarr;</a>
            </div>

            @if ($latestArticles->isEmpty())
                <x-empty-state icon="document-text" :title="__('articles.empty_title')" />
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($latestArticles as $article)
                        <x-card hoverable class="flex flex-col overflow-hidden !p-0">
                            <a href="{{ route('articles.show', ['locale' => app()->getLocale(), 'slug' => $article->slug]) }}" wire:navigate>
                                <img
                                    src="{{ $article->getFirstMediaUrl('featured_image', 'card') ?: asset('images/article-placeholder.png') }}"
                                    alt="{{ $article->title }}"
                                    class="aspect-video w-full object-cover"
                                >
                                <div class="p-5">
                                    <h3 class="font-semibold text-ink">{{ $article->title }}</h3>
                                    <p class="mt-1 text-sm text-ink/60">{{ $article->excerpt }}</p>
                                    <p class="mt-3 text-xs text-ink/40">{{ $article->reading_time_minutes }} {{ __('articles.min_read') }}</p>
                                </div>
                            </a>
                        </x-card>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- 4. Featured courses -->
    <section class="mx-auto max-w-6xl px-4 py-16" data-aos="fade-up">
        <h2 class="mb-8 text-2xl font-bold text-ink">{{ __('home.featured_courses_title') }}</h2>

        @if ($featuredCourses->isEmpty())
            <x-empty-state icon="academic-cap" :title="__('home.no_courses_yet')" />
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredCourses as $course)
                    <x-card hoverable class="flex flex-col overflow-hidden !p-0">
                        <a href="{{ route('courses.show', ['locale' => app()->getLocale(), 'slug' => $course->slug]) }}" wire:navigate>
                            <img
                                src="{{ $course->getFirstMediaUrl('cover_image', 'card') ?: asset('images/course-placeholder.png') }}"
                                alt="{{ $course->title }}"
                                class="aspect-video w-full object-cover"
                            >
                            <div class="p-5">
                                <div class="mb-2 flex flex-wrap gap-1.5">
                                    <x-badge color="brand" :text="ucfirst($course->level->value)" />
                                    <x-badge color="neutral" :text="ucfirst($course->audience->value)" />
                                    <x-badge :color="$course->is_free ? 'success' : 'warning'" :text="$course->is_free ? __('common.free') : __('common.paid')" />
                                </div>
                                <h3 class="font-semibold text-ink">{{ $course->title }}</h3>
                            </div>
                        </a>
                    </x-card>
                @endforeach
            </div>
        @endif
    </section>

    <!-- 5. Research highlight -->
    <section class="bg-surface px-4 py-16" data-aos="fade-up">
        <div class="mx-auto max-w-6xl">
            <h2 class="mb-8 text-2xl font-bold text-ink">{{ __('home.research_title') }}</h2>

            <div class="grid gap-6 sm:grid-cols-2">
                @foreach ($latestResearch as $paper)
                    <x-card hoverable class="flex gap-4">
                        <a href="{{ route('research.show', ['locale' => app()->getLocale(), 'slug' => $paper->slug]) }}" wire:navigate class="flex gap-4">
                            <img
                                src="{{ $paper->getFirstMediaUrl('cover_image') ?: asset('images/research-placeholder.png') }}"
                                alt="{{ $paper->title }}"
                                class="size-20 shrink-0 rounded-lg object-cover"
                            >
                            <div>
                                <h3 class="font-semibold text-ink">{{ $paper->title }}</h3>
                                <p class="mt-1 text-sm text-ink/60">{{ \Illuminate\Support\Str::limit($paper->findings_summary, 90) }}</p>
                            </div>
                        </a>
                    </x-card>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 6. Ask Al Zahra CTA -->
    <section class="bg-brand-secondary px-4 py-16 text-center" data-aos="fade-up">
        <h2 class="text-2xl font-bold text-ink">{{ __('home.ask_title') }}</h2>
        <p class="mt-2 text-ink/70">{{ __('home.ask_subtext') }}</p>
        <div class="mt-6">
            <x-button :href="\Illuminate\Support\Facades\Route::has('consultation.show') ? route('consultation.show', app()->getLocale()) : '#'" variant="primary" size="lg">
                {{ __('home.ask_cta') }}
            </x-button>
        </div>
    </section>

    <!-- 7. Newsletter -->
    <section class="mx-auto max-w-3xl px-4 py-16 text-center" data-aos="fade-up">
        <h2 class="text-2xl font-bold text-ink">{{ __('home.newsletter_title') }}</h2>
        <p class="mt-2 text-ink/60">{{ __('home.newsletter_subtext') }}</p>
        <div class="mt-6 flex justify-center">
            <x-newsletter-form />
        </div>
    </section>
</x-layouts.public>
