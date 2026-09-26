{{--
Filter bar for list pages (works inside a Livewire component: chips call $set, search uses wire:model.live).
Props: chips = [['value' => id|string, 'label' => str, 'count' => ?int], ...], chipsModel (livewire property, e.g. "category"), selected (current chip value or ''),
       searchModel (livewire property, e.g. "search" or null to hide search), search (current search text), searchLabel, placeholder, allLabel, chipsLabel,
       count (result total, null hides), clearModels (extra livewire properties to reset with Clear), sticky bool.
Default slot: extra controls (selects) rendered next to the search field.
--}}
@props([
    'chips' => [], 'chipsModel' => 'category', 'selected' => '', 'searchModel' => 'search', 'search' => '', 'searchLabel' => null, 'placeholder' => null,
    'allLabel' => null, 'chipsLabel' => null, 'count' => null, 'clearModels' => [], 'sticky' => false,
])

@php
$uid = 'fb-' . \Illuminate\Support\Str::random(5);
$models = array_values(array_unique(array_filter(array_merge($chips ? [$chipsModel] : [], $searchModel ? [$searchModel] : [], $clearModels))));
$active = ((string) $selected !== '') || ((string) $search !== '');
$clearJs = implode('; ', array_map(fn ($m) => "\$wire.set('" . $m . "', '')", $models));
@endphp

<div {{ $attributes->merge(['class' => 'relative z-sticky min-w-0 rounded-panel border bg-surface-raised p-3 shadow-soft sm:p-4 ' . ($sticky ? 'sticky top-[var(--filter-top,4.5rem)] supports-[backdrop-filter]:bg-surface-raised/85 supports-[backdrop-filter]:backdrop-blur-md' : '')]) }} role="search">
    <div class="flex flex-col gap-3 md:flex-row md:items-center">
        @if ($searchModel)
            <div class="relative min-w-0 flex-1">
                <label for="{{ $uid }}-q" class="sr-only">{{ $searchLabel ?? __('kit_sections.search') }}</label>
                <x-icon name="magnifying-glass" class="pointer-events-none absolute start-4 top-1/2 size-5 -translate-y-1/2 text-muted" aria-hidden="true" />
                <input id="{{ $uid }}-q" type="search" name="{{ $searchModel }}" value="{{ $search }}" inputmode="search" enterkeyhint="search" autocomplete="off"
                    wire:model.live.debounce.400ms="{{ $searchModel }}" placeholder="{{ $placeholder ?? __('kit_sections.search_placeholder') }}"
                    class="min-h-12 w-full rounded-xl border-[var(--border-color)] bg-surface ps-12 pe-4 text-base text-strong placeholder:text-muted focus:border-brand-primary focus:ring-brand-primary">
            </div>
        @endif
        @if (trim((string) $slot) !== '')<div class="flex min-w-0 flex-col gap-3 sm:flex-row sm:flex-wrap">{{ $slot }}</div>@endif
    </div>

    @if (count($chips))
        <div class="relative mt-3 -mx-3 sm:-mx-4">
            <div class="scroll-x no-scrollbar flex snap-x gap-2 px-3 pb-1 sm:px-4" role="group" aria-label="{{ $chipsLabel ?? __('kit_sections.filter_by') }}">
                @foreach (array_merge([['value' => '', 'label' => $allLabel ?? __('kit_sections.all'), 'count' => null]], $chips) as $chip)
                    @php $on = (string) $selected === (string) $chip['value']; @endphp
                    <button type="button" wire:click="$set('{{ $chipsModel }}', '{{ $chip['value'] }}')" aria-pressed="{{ $on ? 'true' : 'false' }}"
                        class="focus-ring inline-flex min-h-11 shrink-0 snap-start items-center gap-2 rounded-full border px-4 text-sm font-semibold transition duration-fast ease-enter active:scale-[0.97] {{ $on ? 'border-brand-primary bg-brand-primary text-on-brand shadow-soft' : 'bg-surface text-body [@media(hover:hover)]:hover:border-brand-primary [@media(hover:hover)]:hover:text-brand-primary' }}">
                        <span class="whitespace-nowrap">{{ $chip['label'] }}</span>
                        @if (isset($chip['count']))<span class="rounded-full px-1.5 text-xs tabular-nums {{ $on ? 'bg-white/20' : 'bg-surface-sunken text-muted' }}">{{ $chip['count'] }}</span>@endif
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    @if ($count !== null || $active)
        <div class="mt-3 flex min-h-8 flex-wrap items-center justify-between gap-x-4 gap-y-1 text-sm">
            <p class="flex items-center gap-2 text-body" role="status" aria-live="polite">
                <span wire:loading.delay class="star-loader hidden !size-4" aria-hidden="true"></span>
                @if ($count !== null){{ trans_choice('kit_sections.results', $count, ['count' => $count]) }}@endif
            </p>
            @if ($active && $models)
                <button type="button" x-on:click="{{ $clearJs }}" class="focus-ring tap-target gap-1.5 rounded-full px-3 font-semibold text-brand-primary transition duration-fast active:scale-[0.97] [@media(hover:hover)]:hover:bg-tint">
                    <x-icon name="x-mark" class="size-4" aria-hidden="true" />{{ __('kit_sections.clear_filters') }}
                </button>
            @endif
        </div>
    @endif
</div>
