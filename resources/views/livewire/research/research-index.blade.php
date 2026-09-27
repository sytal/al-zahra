@php
    $yearKeys = array_keys($years);
    $yearMin = $yearKeys ? min($yearKeys) : null;
    $yearMax = $yearKeys ? max($yearKeys) : null;
    $ctrl = 'min-h-12 w-full rounded-xl border-[var(--border-color)] bg-surface ps-4 pe-10 text-base text-strong focus:border-brand-primary focus:ring-brand-primary sm:w-52';
@endphp

<div>
    <section class="relative isolate overflow-hidden bg-section-dark">
        <span class="glow-gold -top-40 end-[-6rem] opacity-50" style="--glow-size: 28rem" aria-hidden="true"></span>
        <span class="light-rays" aria-hidden="true"></span>
        <span class="bg-pattern-neural pointer-events-none absolute inset-0 -z-10" aria-hidden="true"></span>

        <div class="container-page grid items-center gap-10 pb-10 pt-12 sm:pb-12 sm:pt-16 lg:grid-cols-12 lg:pb-14 lg:pt-20">
            <div class="min-w-0 lg:col-span-7">
                <p class="eyebrow mb-4 flex items-center gap-2"><span class="star-mark text-sm" aria-hidden="true"></span>{{ __('lists_ui.rs_eyebrow') }}</p>
                <h1 class="heading-display break-words">{{ __('research.page_title') }}</h1>
                <p class="lead mt-4 max-w-xl">{{ __('research.page_intro') }}</p>

                <dl class="mt-8 grid max-w-xl grid-cols-1 gap-3 xs:grid-cols-3">
                    <div class="glass rounded-2xl border px-4 py-3">
                        <dt class="text-xs font-semibold text-muted">{{ __('lists_ui.rs_stat_papers') }}</dt>
                        <dd class="numeral-display text-gradient-brand !text-4xl" x-data="counter({ to: {{ (int) $stats['total'] }} })" x-text="display">{{ (int) $stats['total'] }}</dd>
                    </div>
                    <div class="glass rounded-2xl border px-4 py-3">
                        <dt class="text-xs font-semibold text-muted">{{ __('lists_ui.rs_stat_topics') }}</dt>
                        <dd class="numeral-display text-gradient-brand !text-4xl" x-data="counter({ to: {{ count($chips) }} })" x-text="display">{{ count($chips) }}</dd>
                    </div>
                    @if ($yearMin)
                        <div class="glass rounded-2xl border px-4 py-3">
                            <dt class="text-xs font-semibold text-muted">{{ __('lists_ui.rs_stat_span') }}</dt>
                            <dd class="mt-2 font-display text-xl font-bold tabular-nums text-strong">{{ $yearMin }}@if ($yearMax !== $yearMin)<span class="text-muted"> - </span>{{ $yearMax }}@endif</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="relative mx-auto hidden w-full max-w-sm lg:col-span-5 lg:block lg:max-w-none">
                <div class="absolute inset-x-6 top-4 h-full -rotate-3 rounded-card border bg-surface-sunken/60" aria-hidden="true"></div>
                <div class="absolute inset-x-3 top-2 h-full rotate-2 rounded-card border bg-surface-raised/60" aria-hidden="true"></div>
                <div class="glass relative z-raised rounded-card border p-5 shadow-float">
                    <img src="{{ asset('images/illustrations/illus-research.svg') }}" alt="" width="400" height="300" decoding="async" fetchpriority="high" class="h-auto w-full">
                    <p class="mt-3 flex items-center gap-2 border-t border-dashed border-strong pt-3 text-xs text-muted">
                        <x-icon name="bookmark" class="size-4" aria-hidden="true" />
                        {{ __('lists_ui.rs_cite_hint') }}
                    </p>
                </div>
            </div>
        </div>
        <span class="gold-thread absolute inset-x-0 bottom-0" aria-hidden="true"></span>
    </section>

    <x-section variant="tinted" tight id="research-results" class="!pt-5 sm:!pt-6">
        <x-filter-bar sticky :chips="$chips" chips-model="category" :selected="$category ?? ''" search-model="search" :search="$search"
            :search-label="__('research.search_label')" :placeholder="__('lists_ui.rs_search')" :all-label="__('lists_ui.rs_all')"
            :chips-label="__('lists_ui.rs_chips')" :count="$papers->total()" :clear-models="['year']">
            @if (count($years))
                <div>
                    <label for="rs-year" class="sr-only">{{ __('lists_ui.rs_year') }}</label>
                    <select id="rs-year" wire:model.live="year" class="{{ $ctrl }}">
                        <option value="">{{ __('lists_ui.rs_all_years') }}</option>
                        @foreach ($years as $y => $n)
                            <option value="{{ $y }}">{{ $y }} ({{ $n }})</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </x-filter-bar>

        <div class="mt-8">
            <div wire:loading.delay.shorter.grid class="hidden grid gap-5 lg:grid-cols-2 lg:gap-6" aria-hidden="true">
                @foreach (range(1, 4) as $i)
                    <x-research-card skeleton />
                @endforeach
            </div>

            <div wire:loading.delay.shorter.remove>
                @if ($papers->isEmpty())
                    <x-empty-state icon="beaker" illustration="research" :title="__('research.empty_title')" :message="__('research.empty_message')">
                        <x-slot:action>
                            <x-button type="button" variant="outline" icon="x-mark" x-on:click="$wire.set('category', ''); $wire.set('search', ''); $wire.set('year', '')">{{ __('lists_ui.clear') }}</x-button>
                        </x-slot:action>
                    </x-empty-state>
                @else
                    <div class="stagger grid gap-5 lg:grid-cols-2 lg:gap-6">
                        @foreach ($papers as $paper)
                            <x-research-card :paper="$paper" as="h2" wire:key="paper-{{ $paper->id }}" />
                        @endforeach
                    </div>

                    <x-pagination :paginator="$papers" />
                @endif
            </div>
        </div>
    </x-section>
</div>
