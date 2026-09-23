<x-layouts.public>
    <x-slot name="seo">
        <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" />
    </x-slot>

    <x-detail-layout :title="$resource->title">
        <x-slot name="meta">
            <x-breadcrumbs :items="[
                ['label' => __('nav.resources'), 'url' => route('resources.index', app()->getLocale())],
                ['label' => $resource->title],
            ]" />
            @if ($resource->category)
                <x-badge color="brand" :text="$resource->category->name" />
            @endif
            <x-badge color="neutral" :text="ucfirst($resource->resource_type->value)" />
            <x-badge :color="$resource->is_free ? 'success' : 'warning'" :text="$resource->is_free ? __('common.free') : __('common.paid')" />
        </x-slot>

        @if ($resource->hasMedia('thumbnail'))
            <img src="{{ $resource->getFirstMediaUrl('thumbnail') }}" alt="{{ $resource->title }}" class="mb-8 w-full rounded-xl">
        @endif

        <p class="text-ink/80">{{ $resource->description }}</p>

        @if (in_array($resource->resource_type->value, ['questionnaire', 'guide']))
            <div class="mt-6 rounded-xl border border-brand-secondary/30 bg-brand-secondary/10 p-4 text-sm text-ink/70">
                {{ __('resources.disclaimer') }}
            </div>
        @endif

        <div class="mt-8">
            @if ($resource->is_free)
                <form method="POST" action="{{ route('resources.download', ['locale' => app()->getLocale(), 'slug' => $resource->slug]) }}">
                    @csrf
                    <x-button type="submit" variant="primary" icon="arrow-down-tray">{{ __('resources.download') }}</x-button>
                </form>
            @else
                <x-button variant="secondary" disabled title="{{ __('resources.coming_soon') }}">{{ __('resources.coming_soon') }}</x-button>
            @endif
        </div>

        <x-slot name="related">
            @if ($related->isNotEmpty())
                <h2 class="mb-4 text-xl font-semibold text-ink">{{ __('resources.related_title') }}</h2>
                <div class="grid gap-6 sm:grid-cols-3">
                    @foreach ($related as $item)
                        <x-card hoverable>
                            <a href="{{ route('resources.show', ['locale' => app()->getLocale(), 'slug' => $item->slug]) }}" wire:navigate>
                                <h3 class="font-medium text-ink">{{ $item->title }}</h3>
                            </a>
                        </x-card>
                    @endforeach
                </div>
            @endif
        </x-slot>
    </x-detail-layout>
</x-layouts.public>
