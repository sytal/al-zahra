@props(['name' => 'search', 'label' => null, 'placeholder' => null, 'hideLabel' => true, 'loadingTarget' => null, 'size' => 'md', 'id' => null])

@php
$fid = $id ?? str_replace(['.', '[', ']'], '-', (string) $name);
$text = $label ?? __('kit_forms.search');
@endphp

<x-forms._field-wrapper :name="$name" :id="$fid" :label="$text" :hide-label="$hideLabel" :size="$size" prefix-icon="magnifying-glass" :class="$attributes->get('class')">
    <input
        type="search"
        id="{{ $fid }}"
        name="{{ $name }}"
        data-control
        x-ref="field"
        inputmode="search"
        enterkeyhint="search"
        autocomplete="off"
        autocapitalize="off"
        spellcheck="false"
        placeholder="{{ $placeholder ?? $text }}"
        {{ $attributes->except('class')->class('block w-full min-w-0 flex-1 rounded-xl border-0 bg-transparent py-2.5 ps-2.5 pe-1 text-base text-strong placeholder:text-subtle focus:border-0 focus:shadow-none focus:outline-none focus:ring-0 [&::-webkit-search-cancel-button]:hidden [&::-webkit-search-decoration]:hidden') }}
    />

    <x-slot:suffix>
        <span class="pointer-events-none me-1 hidden shrink-0 items-center" wire:loading.flex.delay @if ($loadingTarget) wire:target="{{ $loadingTarget }}" @endif>
            <x-spinner size="sm" />
        </span>
        <button
            type="button"
            x-show="n > 0"
            x-cloak
            x-transition.opacity.duration.150ms
            x-on:click="$refs.field.value = ''; $refs.field.dispatchEvent(new Event('input', { bubbles: true })); $refs.field.focus()"
            aria-label="{{ __('kit_forms.clear') }}"
            class="tap-target me-1 shrink-0 rounded-lg text-muted outline-none transition-[color,transform] duration-fast focus-visible:ring-2 focus-visible:ring-brand active:scale-95 [@media(hover:hover)]:hover:text-strong"
        >
            <x-icon name="x-mark" size="size-5" aria-hidden="true" />
        </button>
    </x-slot:suffix>
</x-forms._field-wrapper>
