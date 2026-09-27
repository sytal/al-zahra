@php
$loc = app()->getLocale();
$consult = route('consultation.show', $loc);
$creds = collect($director?->credentials ?? [])->filter()->values();
$interests = collect($director?->research_interests ?? [])->filter()->values();
$cover = $director?->getFirstMediaUrl('cover_photo');
$social = collect($director?->social_links ?? [])->filter()->all();
$values = [
    ['icon' => 'beaker', 'n' => 1],
    ['icon' => 'light-bulb', 'n' => 2],
    ['icon' => 'heart', 'n' => 3],
    ['icon' => 'globe-alt', 'n' => 4],
];
$statRows = [
    ['to' => $stats['articles'], 'label' => __('about_ui.stat_articles'), 'icon' => 'document-text'],
    ['to' => $stats['courses'], 'label' => __('about_ui.stat_courses'), 'icon' => 'academic-cap'],
    ['to' => $stats['papers'], 'label' => __('about_ui.stat_papers'), 'icon' => 'beaker'],
];
@endphp

<x-layouts.public>
    <x-slot name="seo">
        <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" />
    </x-slot>

    @if ($director)
        {{-- Cover hero --}}
        <section class="bg-section-dark relative isolate overflow-hidden">
            @if ($cover)
                <img src="{{ $cover }}" alt="" width="1600" height="600" fetchpriority="high" decoding="async" class="absolute inset-0 -z-10 size-full object-cover opacity-30">
            @endif
            <span class="glow-gold -top-32 end-[-6rem] opacity-40" style="--glow-size: 28rem" aria-hidden="true"></span>
            <span class="light-rays" aria-hidden="true"></span>
            <span class="bg-pattern-islamic pointer-events-none absolute inset-0 -z-10" aria-hidden="true"></span>
            <div class="container-page py-16 sm:py-20 lg:py-28">
                <div class="max-w-3xl">
                    <p class="eyebrow mb-4">{{ __('about_ui.hero_eyebrow') }}</p>
                    <h1 class="heading-display break-words">{{ $director->full_name }}</h1>
                    <p class="mt-3 text-xl font-semibold text-secondary-text">{{ $director->professional_title }}</p>
                    <p class="lead mt-5 max-w-2xl">{{ $director->tagline ?: __('about_ui.hero_lead_fallback') }}</p>
                    <div class="mt-8 flex flex-col gap-3 xs:flex-row xs:flex-wrap">
                        <x-button :href="$consult" size="lg">{{ __('about_ui.cta_book') }}</x-button>
                        <x-button :href="route('research.index', $loc)" variant="outline" size="lg">{{ __('home_ui.research_all') }}</x-button>
                    </div>
                </div>
            </div>
            <span class="gold-thread absolute inset-x-0 bottom-0" aria-hidden="true"></span>
        </section>

        {{-- Full profile --}}
        <x-section variant="light">
            <x-director-profile :compact="false" />
        </x-section>

        {{-- Credentials timeline + interests --}}
        <x-section variant="tinted">
            <div class="grid gap-12 lg:grid-cols-2 lg:gap-16">
                <div class="min-w-0">
                    <x-section-heading :title="__('about_ui.timeline_title')" :lead="__('about_ui.timeline_lead')" />
                    @if ($creds->isEmpty())
                        <x-empty-state compact icon="academic-cap" :title="__('about_ui.timeline_empty')" />
                    @else
                        <ol class="relative space-y-6 border-s-2 border-brand-secondary ps-8">
                            @foreach ($creds as $c)
                                <li class="relative min-w-0" data-aos="fade-up">
                                    <span class="star-mark absolute -start-[2.65rem] top-1 bg-[var(--color-surface-sunken)] text-lg" aria-hidden="true"></span>
                                    <p class="card-surface break-words p-4 font-semibold text-strong">{{ $c }}</p>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
                <div class="min-w-0">
                    <x-section-heading :title="__('about_ui.interests_title')" />
                    @if ($interests->isEmpty())
                        <x-empty-state compact icon="beaker" :title="__('about_ui.interests_empty')" />
                    @else
                        <ul class="flex flex-wrap gap-3">
                            @foreach ($interests as $i)<li><x-tag-pill :text="$i" class="!min-h-11 !px-4 !text-base" /></li>@endforeach
                        </ul>
                    @endif
                    <dl class="mt-10 grid grid-cols-3 gap-4 text-center">
                        @foreach ($statRows as $s)
                            <div class="card-surface min-w-0 p-4">
                                <dd dir="ltr" class="font-display text-3xl font-bold text-brand-primary tabular-nums" x-data="counter({ to: {{ (int) $s['to'] }} })" x-text="display">{{ number_format($s['to']) }}</dd>
                                <dt class="mt-1 text-xs text-body sm:text-sm">{{ $s['label'] }}</dt>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </div>
        </x-section>

        {{-- Mission / Vision --}}
        @if ($mission || $vision)
            <x-section variant="light">
                <x-section-heading align="center" :title="__('about.mission_title').' / '.__('about.vision_title')" />
                <x-bento class="!grid-cols-1 md:!grid-cols-2 lg:!grid-cols-2">
                    @if ($mission)
                        <x-bento.tile tone="brand" icon="flag" :title="__('about.mission_title')" class="!p-8"><p class="text-lg">{{ $mission }}</p></x-bento.tile>
                    @endif
                    @if ($vision)
                        <x-bento.tile tone="dark" icon="eye" :title="__('about.vision_title')" class="!p-8"><p class="text-lg">{{ $vision }}</p></x-bento.tile>
                    @endif
                </x-bento>
            </x-section>
        @endif

        {{-- Values --}}
        <x-section variant="sunken">
            <x-section-heading align="center" :eyebrow="__('about_ui.values_eyebrow')" :title="__('about_ui.values_title')" />
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4 stagger">
                @foreach ($values as $v)
                    <x-feature-card :icon="$v['icon']" :title="__('about_ui.value_'.$v['n'].'_title')" :text="__('about_ui.value_'.$v['n'].'_text')" />
                @endforeach
            </div>
        </x-section>

        {{-- Resources CTA --}}
        <x-section variant="pattern" tight>
            <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
                <div class="min-w-0 max-w-2xl">
                    <h2 class="heading-3 break-words">{{ __('about_ui.resources_title') }}</h2>
                    <p class="mt-2 text-body">{{ __('about_ui.resources_text') }}</p>
                </div>
                <x-button :href="route('resources.index', $loc)" variant="outline" size="lg">{{ __('about_ui.resources_cta') }}</x-button>
            </div>
        </x-section>

        {{-- Social / contact --}}
        <x-section variant="light" tight>
            <x-section-heading align="center" :title="__('about_ui.contact_title')" />
            <div class="grid items-start gap-6 lg:grid-cols-2">
                <div class="card-surface min-w-0 space-y-4 p-6">
                    @if ($email)
                        <p class="flex items-center gap-3"><x-icon name="envelope" class="size-6 shrink-0 text-brand-primary" aria-hidden="true" /><span class="min-w-0"><span class="block text-xs text-muted">{{ __('about_ui.contact_email') }}</span><a href="mailto:{{ $email }}" class="link-underline break-anywhere font-semibold text-strong">{{ $email }}</a></span></p>
                    @endif
                    @if ($phone)
                        <p class="flex items-center gap-3"><x-icon name="phone" class="size-6 shrink-0 text-brand-primary" aria-hidden="true" /><span class="min-w-0"><span class="block text-xs text-muted">{{ __('about_ui.contact_phone') }}</span><a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" dir="ltr" class="link-underline font-semibold text-strong">{{ $phone }}</a></span></p>
                    @endif
                    @if ($social)
                        <x-social-links :links="$social" />
                    @endif
                    @if (! $email && ! $phone && ! $social)
                        <x-button :href="route('contact.show', $loc)" variant="outline">{{ __('nav.contact') }}</x-button>
                    @endif
                </div>
                @if (count($hours))
                    <x-opening-hours :hours="$hours" />
                @endif
            </div>
        </x-section>

        {{-- CTA band --}}
        <x-section variant="light" tight>
            <x-cta-band :title="__('about_ui.cta_title')" :lead="__('about_ui.cta_lead')"
                :primaryLabel="__('about_ui.cta_book')" :primaryUrl="$consult" :secondaryLabel="__('about_ui.cta_secondary')" :secondaryUrl="$consult" />
        </x-section>
    @else
        <x-section variant="light">
            <x-empty-state icon="user" illustration="empty" :title="__('about_ui.empty_title')" :message="__('about_ui.empty_text')" />
        </x-section>
    @endif
</x-layouts.public>
