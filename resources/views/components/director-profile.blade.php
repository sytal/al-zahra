{{--
Director portrait + bio. Props: compact (bool, default true; false renders the full profile), director (optional override: Director model or array with full_name, professional_title, tagline, bio_full, credentials, research_interests, social_links, photo; defaults to the published Director),
readMoreUrl (adds a "Read more" link in the compact card).
--}}
@props(['compact' => true, 'director' => null, 'readMoreUrl' => null])

@php
$d = $director ?? \App\Modules\Director\Models\Director::where('is_published', true)->first();
$name = data_get($d, 'full_name');
$photo = data_get($d, 'photo') ?: (is_object($d) && method_exists($d, 'getFirstMediaUrl') ? $d->getFirstMediaUrl('profile_photo') : null);
$hasPhoto = (bool) $photo;
$placeholder = asset('images/director-placeholder.svg');
$creds = collect(data_get($d, 'credentials', []) ?: [])->filter()->values();
$interests = collect(data_get($d, 'research_interests', []) ?: [])->filter()->values();
@endphp

@if ($d)
    @if ($compact)
        <div {{ $attributes->merge(['class' => 'card-surface relative flex min-w-0 items-center gap-4 p-4 sm:gap-5 sm:p-5']) }}>
            <span class="relative shrink-0">
                <img src="{{ $photo ?: $placeholder }}" alt="{{ $name }}" width="80" height="80" loading="lazy" decoding="async" class="size-16 rounded-full object-cover ring-2 ring-[var(--color-brand-secondary)] ring-offset-2 ring-offset-[var(--color-surface-raised)] sm:size-20 {{ $hasPhoto ? '' : 'bg-tint object-top' }}">
                <span class="star-mark absolute -end-1 -top-1 text-base" aria-hidden="true"></span>
            </span>
            <div class="min-w-0">
                <h3 class="heading-4 break-words">{{ $name }}</h3>
                <p class="text-sm font-semibold text-secondary-text">{{ data_get($d, 'professional_title') }}</p>
                @if (data_get($d, 'tagline'))<p class="mt-1 line-clamp-2 text-sm text-body">{{ data_get($d, 'tagline') }}</p>@endif
                @if ($readMoreUrl)
                    <a href="{{ $readMoreUrl }}" wire:navigate class="focus-ring link-underline mt-2 inline-flex min-h-9 items-center gap-1.5 text-sm font-semibold text-brand-primary">{{ __('kit_sections.read_more') }}<x-icon name="arrow-right" class="size-4 rtl:-scale-x-100" aria-hidden="true" /></a>
                @endif
            </div>
        </div>
    @else
        <div {{ $attributes->merge(['class' => 'grid min-w-0 items-start gap-8 md:grid-cols-[minmax(0,17rem)_minmax(0,1fr)] md:gap-10 lg:grid-cols-[minmax(0,21rem)_minmax(0,1fr)] lg:gap-14']) }}>
            <div class="relative isolate mx-auto w-full max-w-xs md:max-w-none">
                <span class="star-mark pointer-events-none absolute -start-10 -top-10 -z-10 text-[13rem] opacity-20 lg:text-[16rem]" aria-hidden="true"></span>
                <span class="absolute inset-0 -z-10 translate-x-3 translate-y-3 rounded-panel border-2 border-brand-secondary rtl:-translate-x-3" aria-hidden="true"></span>
                <div class="relative aspect-[4/5] overflow-hidden rounded-panel bg-tint shadow-float">
                    <img src="{{ $photo ?: $placeholder }}" alt="{{ $name }}" width="400" height="500" loading="lazy" decoding="async" class="size-full object-cover {{ $hasPhoto ? 'dark:brightness-95' : '' }}">
                </div>
                <div class="glass absolute inset-x-4 -bottom-5 px-4 py-3 text-center">
                    <p class="truncate text-sm font-semibold text-strong">{{ $name }}</p>
                    <p class="truncate text-xs text-secondary-text">{{ data_get($d, 'professional_title') }}</p>
                </div>
            </div>
            <div class="min-w-0 pt-6 md:pt-0">
                <h2 class="heading-1 break-words">{{ $name }}</h2>
                <p class="mt-1 text-lg font-semibold text-secondary-text">{{ data_get($d, 'professional_title') }}</p>
                @if (data_get($d, 'tagline'))<p class="pull-quote mt-6">{{ data_get($d, 'tagline') }}</p>@endif
                @if (data_get($d, 'bio_full'))<p class="lead mt-6 whitespace-pre-line">{{ data_get($d, 'bio_full') }}</p>@endif

                @if ($creds->isNotEmpty())
                    <h3 class="heading-4 mt-8">{{ __('kit_sections.credentials') }}</h3>
                    <ul class="star-bullet mt-3 space-y-2 text-body">
                        @foreach ($creds as $credential)<li class="break-words">{{ $credential }}</li>@endforeach
                    </ul>
                @endif

                @if ($interests->isNotEmpty())
                    <h3 class="heading-4 mt-8">{{ __('kit_sections.research_interests') }}</h3>
                    <ul class="mt-3 flex flex-wrap gap-2">
                        @foreach ($interests as $interest)<li><x-tag-pill :text="$interest" /></li>@endforeach
                    </ul>
                @endif

                @if (!empty(data_get($d, 'social_links')))
                    <x-social-links class="mt-8" :links="data_get($d, 'social_links')" />
                @endif
            </div>
        </div>
    @endif
@endif
