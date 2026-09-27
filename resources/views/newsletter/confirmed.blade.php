<x-layouts.minimal>
    <x-slot:seo><title>{{ __('newsletter.confirm_title') }} | {{ config('app.name') }}</title></x-slot:seo>
    <div class="gradient-border relative w-full max-w-xl overflow-hidden rounded-panel bg-surface-raised p-6 text-center shadow-lift sm:p-10">
        <div class="glow-gold -top-20 start-1/2 -translate-x-1/2 rtl:translate-x-1/2" style="--glow-size: 16rem" aria-hidden="true"></div>
        <div class="star-burst relative mx-auto w-fit" x-data="starBurst" x-init="$nextTick(() => fire())" :class="{ 'is-bursting': bursting }">
            <img src="{{ asset('images/illustrations/illus-success.svg') }}" width="400" height="300" alt="" aria-hidden="true" class="mx-auto h-40 w-auto max-w-full sm:h-48" loading="eager" decoding="async">
        </div>
        <h1 class="relative mt-4 font-display text-3xl font-bold text-strong sm:text-4xl">{{ __('newsletter.confirm_title') }}</h1>
        <p class="relative mx-auto mt-2 max-w-md text-body">{{ __('newsletter.confirm_message') }}</p>

        <p class="eyebrow relative mt-8 inline-flex items-center gap-2 text-secondary-text"><span class="star-mark text-sm" aria-hidden="true"></span>{{ __('auth_ui.confirmed_next') }}</p>
        <div class="relative mt-4 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button :href="route('articles.index', app()->getLocale())" navigate variant="primary" icon-end="arrow-right">{{ __('auth_ui.confirmed_articles') }}</x-button>
            <x-button :href="route('courses.index', app()->getLocale())" navigate variant="outline">{{ __('auth_ui.confirmed_courses') }}</x-button>
        </div>
        <p class="relative mt-6 text-sm">
            <a href="{{ route('home', app()->getLocale()) }}" wire:navigate class="link-underline font-medium text-link">{{ __('newsletter.confirm_home_link') }}</a>
        </p>
    </div>
</x-layouts.minimal>
