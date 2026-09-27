@php
    $typeIcons = ['pdf' => 'document-text', 'template' => 'document-duplicate', 'questionnaire' => 'clipboard-document-list', 'guide' => 'book-open'];
    $tabs = array_merge(
        [['value' => '', 'label' => __('lists_ui.rc_all_types'), 'icon' => 'squares-2x2', 'count' => (int) $stats['total']]],
        array_map(fn ($c) => ['value' => $c->value, 'label' => __('enums.resource_type.' . $c->value), 'icon' => $typeIcons[$c->value] ?? 'document', 'count' => (int) ($types[$c->value] ?? 0)], \App\Support\Enums\ResourceType::cases())
    );
    $pricingOpts = ['' => __('kit_sections.all'), 'free' => __('common.free'), 'paid' => __('common.paid')];
    $grid = 'grid gap-x-5 gap-y-8 sm:grid-cols-2 xl:grid-cols-3';
@endphp

<div>
    <section class="relative isolate overflow-hidden bg-hero-gradient">
        <span class="glow-teal -top-28 end-[-7rem] opacity-80" style="--glow-size: 26rem" aria-hidden="true"></span>
        <span class="glow-gold bottom-[-8rem] start-[-6rem] opacity-50" style="--glow-size: 22rem" aria-hidden="true"></span>
        <span class="bg-pattern-dots pointer-events-none absolute inset-0 -z-10" aria-hidden="true"></span>

        <div class="container-page grid items-center gap-8 pt-10 sm:pt-14 md:grid-cols-12 lg:pt-16">
            <div class="min-w-0 md:col-span-7">
                <p class="eyebrow mb-4 flex items-center gap-2"><span class="star-mark text-sm" aria-hidden="true"></span>{{ __('lists_ui.rc_eyebrow') }}</p>
                <h1 class="heading-display break-words">{{ __('resources.page_title') }}</h1>
                <p class="lead mt-4 max-w-xl">{{ __('resources.page_intro') }}</p>
                <p class="mt-6 flex flex-wrap items-center gap-2 text-sm">
                    <x-badge color="brand" icon="arrow-down-tray" :text="trans_choice('lists_ui.rc_count', (int) $stats['total'], ['count' => (int) $stats['total']])" />
                    @if ($stats['free'] > 0)
                        <x-badge color="success" dot :text="trans_choice('lists_ui.rc_free_count', (int) $stats['free'], ['count' => (int) $stats['free']])" />
                    @endif
                </p>
            </div>
            <div class="relative hidden md:col-span-5 md:block">
                <span class="star-mark absolute -end-4 -top-4 z-0 text-7xl opacity-25 motion-safe:animate-float" aria-hidden="true"></span>
                <img src="{{ asset('images/illustrations/illus-resources.svg') }}" alt="" width="400" height="300" decoding="async" fetchpriority="high" class="relative z-raised h-auto w-full drop-shadow-xl">
            </div>
        </div>

        <div class="container-page mt-8">
            <div class="scroll-x no-scrollbar -mb-px flex snap-x items-end gap-1.5" role="group" aria-label="{{ __('lists_ui.rc_tabs') }}">
                @foreach ($tabs as $tab)
                    @php $on = (string) $activeType === (string) $tab['value']; @endphp
                    <button type="button" wire:click="$set('type', '{{ $tab['value'] }}')" aria-pressed="{{ $on ? 'true' : 'false' }}"
                        class="focus-ring inline-flex min-h-11 shrink-0 snap-start items-center gap-2 rounded-t-xl border border-b-0 px-4 text-sm font-semibold transition duration-fast ease-enter {{ $on ? 'bg-surface-sunken pt-1 text-brand-primary' : 'bg-surface/60 text-body [@media(hover:hover)]:hover:bg-surface [@media(hover:hover)]:hover:text-brand-primary' }}">
                        <x-icon :name="$tab['icon']" class="size-4 shrink-0" aria-hidden="true" />
                        <span class="whitespace-nowrap">{{ $tab['label'] }}</span>
                        <span class="rounded-full bg-surface-sunken px-1.5 text-xs tabular-nums text-muted">{{ $tab['count'] }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <x-section variant="sunken" tight id="resources-results" class="!pt-5 sm:!pt-6">
        <x-filter-bar sticky :chips="$chips" chips-model="category" :selected="$category ?? ''" search-model="search" :search="$search"
            :search-label="__('resources.search_label')" :placeholder="__('lists_ui.rc_search')" :all-label="__('lists_ui.rc_all')"
            :chips-label="__('lists_ui.rc_chips')" :count="$resources->total()" :clear-models="['type', 'pricing']">
            <div class="flex gap-1 rounded-xl border bg-surface p-1" role="group" aria-label="{{ __('lists_ui.rc_pricing') }}">
                @foreach ($pricingOpts as $val => $label)
                    @php $on = (string) $activePricing === (string) $val; @endphp
                    <button type="button" wire:click="$set('pricing', '{{ $val }}')" aria-pressed="{{ $on ? 'true' : 'false' }}"
                        class="focus-ring min-h-10 flex-1 rounded-lg px-3 text-sm font-semibold transition duration-fast active:scale-[0.97] sm:flex-none {{ $on ? 'bg-brand-primary text-on-brand shadow-soft' : 'text-body [@media(hover:hover)]:hover:bg-tint' }}">{{ $label }}</button>
                @endforeach
            </div>
        </x-filter-bar>

        <div class="mt-8">
            <div wire:loading.delay.shorter.grid class="hidden {{ $grid }}" aria-hidden="true">
                @foreach (range(1, 6) as $i)
                    <x-resource-card skeleton />
                @endforeach
            </div>

            <div wire:loading.delay.shorter.remove>
                @if ($resources->isEmpty())
                    <x-empty-state icon="folder-open" illustration="resources" :title="__('resources.empty_title')" :message="__('resources.empty_message')">
                        <x-slot:action>
                            <x-button type="button" variant="outline" icon="x-mark" x-on:click="$wire.set('category', ''); $wire.set('search', ''); $wire.set('type', ''); $wire.set('pricing', '')">{{ __('lists_ui.clear') }}</x-button>
                        </x-slot:action>
                    </x-empty-state>
                @else
                    <div class="stagger {{ $grid }}">
                        @foreach ($resources as $resource)
                            <x-resource-card :resource="$resource" as="h2" wire:key="resource-{{ $resource->id }}" />
                        @endforeach
                    </div>

                    <x-pagination :paginator="$resources" />
                @endif
            </div>
        </div>
    </x-section>
</div>
