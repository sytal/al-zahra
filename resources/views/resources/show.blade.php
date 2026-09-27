@php
    $locale = app()->getLocale();
    $typeKey = $resource->resource_type->value;
    $typeIcon = ['pdf' => 'document-text', 'template' => 'document-duplicate', 'questionnaire' => 'clipboard-document-list', 'guide' => 'book-open'][$typeKey] ?? 'document';
    $cover = $resource->getFirstMediaUrl('thumbnail');
    $coverFallback = asset('images/resource-placeholder.svg');
    $hasFile = $resource->hasMedia('resource_file');
    $state = ! $resource->is_free ? 'paid' : ($hasFile ? 'free' : 'disabled');
    $showDisclaimer = in_array($typeKey, ['questionnaire', 'guide'], true);
    $downloads = (int) ($resource->download_count ?? 0);
@endphp
<x-layouts.public>
    <x-slot name="seo">
        <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" />
    </x-slot>

    <article class="min-w-0">
        <header class="bg-section-tinted relative isolate overflow-hidden">
            <span class="glow-gold -z-10" style="inset-inline-start:-6rem;bottom:-8rem;--glow-size:24rem" aria-hidden="true"></span>
            <span class="glow-teal -z-10" style="inset-inline-end:-6rem;top:-6rem;--glow-size:24rem" aria-hidden="true"></span>
            <div class="container-page py-8 sm:py-12 lg:py-16">
                <x-breadcrumbs :items="[
                    ['label' => __('nav.resources'), 'url' => route('resources.index', $locale)],
                    ['label' => $resource->title],
                ]" />

                <div class="grid min-w-0 items-center gap-10 md:grid-cols-[minmax(0,18rem)_minmax(0,1fr)] lg:grid-cols-[minmax(0,22rem)_minmax(0,1fr)] lg:gap-16">
                    <div class="relative mx-auto w-full max-w-[18rem] pt-8 md:max-w-none" data-aos="fade-up">
                        <span class="absolute inset-x-3 bottom-0 top-10 translate-y-3 rotate-2 rounded-card border bg-surface-sunken" aria-hidden="true"></span>
                        <span class="absolute inset-x-1.5 bottom-0 top-9 translate-y-1.5 -rotate-1 rounded-card border bg-surface-raised" aria-hidden="true"></span>
                        <span class="absolute start-4 top-0 z-raised inline-flex h-8 max-w-[75%] items-center gap-1.5 rounded-t-xl border border-b-0 bg-surface-raised px-3 text-xs font-semibold text-brand-primary">
                            <x-icon :name="$typeIcon" class="size-4 shrink-0" aria-hidden="true" />
                            <span class="truncate">{{ __('enums.resource_type.'.$typeKey) }}</span>
                        </span>
                        <div class="tilt card-surface relative aspect-[4/5] overflow-hidden !rounded-ss-none shadow-float" x-data="tilt">
                            <img src="{{ $cover ?: $coverFallback }}" alt="{{ $resource->title }}" width="480" height="600" decoding="async" fetchpriority="high"
                                onerror="this.onerror=null;this.src='{{ $coverFallback }}'" class="size-full object-cover dark:brightness-90">
                        </div>
                    </div>

                    <div class="min-w-0" data-aos="fade-up" data-aos-delay="80">
                        <div class="flex flex-wrap items-center gap-2">
                            <x-badge color="neutral" variant="outline" :icon="$typeIcon" :text="__('enums.resource_type.'.$typeKey)" />
                            <x-badge :color="$resource->is_free ? 'success' : 'warning'" dot :text="$resource->is_free ? __('common.free') : __('common.paid')" />
                            @if ($resource->category)<x-badge color="brand" :text="$resource->category->name" />@endif
                        </div>
                        <h1 class="heading-1 mt-5 break-words [text-wrap:balance]">{{ $resource->title }}</h1>
                        <span class="gold-thread mt-6 block max-w-xs" aria-hidden="true"></span>

                        <div class="mt-8">
                            @if ($state === 'free')
                                <x-download-button state="free" :action="route('resources.download', ['locale' => $locale, 'slug' => $resource->slug])" :label="__('resources.download')" />
                                <p class="mt-3 text-sm text-body">{{ __('detail_ui.free_note') }}</p>
                            @elseif ($state === 'paid')
                                <x-download-button state="paid" :label="__('resources.coming_soon')" :tooltip="__('detail_ui.paid_note')" />
                                <p class="mt-3 text-sm text-body">{{ __('detail_ui.paid_note') }}</p>
                            @else
                                <x-download-button state="disabled" :label="__('resources.download')" :tooltip="__('detail_ui.no_file')" />
                                <p class="mt-3 text-sm text-body">{{ __('detail_ui.no_file') }}</p>
                            @endif
                            @if ($downloads > 0)
                                <p class="mt-3 inline-flex items-center gap-1.5 text-xs text-muted"><x-icon name="arrow-down-tray" class="size-4" aria-hidden="true" />{{ __('detail_ui.downloads', ['count' => number_format($downloads)]) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-page py-10 sm:py-14">
            <div class="mx-auto max-w-3xl min-w-0 space-y-8">
                <section aria-labelledby="about-resource" data-aos="fade-up">
                    <h2 id="about-resource" class="heading-3 flex items-center gap-3"><span class="star-mark text-base" aria-hidden="true"></span>{{ __('detail_ui.about_resource') }}</h2>
                    @if (filled($resource->description))
                        <p class="mt-4 whitespace-pre-line break-words text-base text-body sm:text-lg">{{ $resource->description }}</p>
                    @else
                        <p class="mt-4 text-body">{{ __('detail_ui.no_description') }}</p>
                    @endif
                </section>

                @if ($showDisclaimer)
                    <x-alert variant="neutral" icon="shield-exclamation" :title="__('detail_ui.disclaimer_title')" data-aos="fade-up">
                        {{ __('resources.disclaimer') }}
                    </x-alert>
                @endif

                <div class="flex flex-wrap items-center gap-3 border-t border-dashed border-strong pt-6">
                    <x-share-buttons :url="url()->current()" :title="$resource->title" />
                </div>
            </div>
        </div>

        @if ($related->isNotEmpty())
            <section class="bg-section-sunken section" aria-labelledby="related-title">
                <div class="container-page">
                    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
                        <h2 id="related-title" class="heading-2 min-w-0 break-words">{{ __('resources.related_title') }}</h2>
                        <a href="{{ route('resources.index', $locale) }}" wire:navigate class="link-underline focus-ring inline-flex min-h-11 items-center gap-1.5 text-sm font-semibold text-brand-primary">
                            {{ __('detail_ui.back_resources') }}
                            <x-icon name="arrow-right" class="size-4 rtl:-scale-x-100" aria-hidden="true" />
                        </a>
                    </div>
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($related as $item)
                            <div data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}"><x-resource-card :resource="$item" as="h3" /></div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </article>
</x-layouts.public>
