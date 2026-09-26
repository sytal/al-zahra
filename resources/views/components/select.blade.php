@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => null,
    'hint' => null,
    'id' => null,
    'icon' => null,
    'required' => false,
    'optional' => false,
    'error' => null,
    'success' => false,
    'size' => 'md',
    'hideLabel' => false,
])

@php
$fid = $id ?? str_replace(['.', '[', ']'], '-', (string) $name);
$message = $error ?? ($errors->has($name) ? $errors->first($name) : null);
$describedBy = trim(($attributes->has('aria-describedby') ? $attributes->get('aria-describedby') . ' ' : '') . ($hint ? $fid . '-hint ' : '') . ($message ? $fid . '-error' : ''));
@endphp

<x-forms._field-wrapper
    :name="$name" :id="$fid" :label="$label" :hint="$hint" :required="$required" :optional="$optional"
    :error="$message" :success="$success" :size="$size" :hide-label="$hideLabel" :prefix-icon="$icon"
    :disabled="(bool) $attributes->get('disabled')"
    :class="$attributes->get('class')"
>
    <select
        id="{{ $fid }}"
        name="{{ $name }}"
        data-control
        @if ($message) aria-invalid="true" @endif
        @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
        @if ($required) aria-required="true" @endif
        {{ $attributes->except(['class', 'aria-describedby'])->class([
            'block w-full min-w-0 flex-1 cursor-pointer appearance-none truncate rounded-xl border-0 bg-transparent bg-none py-2.5 pe-10 text-base text-strong',
            'focus:border-0 focus:shadow-none focus:outline-none focus:ring-0 disabled:cursor-not-allowed',
            $icon ? 'ps-2.5' : 'ps-3.5',
        ]) }}
    >
        @if ($placeholder !== null)
            <option value="" @disabled($required)>{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected($value !== null && (string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
        {{ $slot }}
    </select>

    <x-slot:suffix>
        <x-icon name="chevron-down" size="size-5" class="pointer-events-none absolute end-3.5 top-1/2 -translate-y-1/2 text-muted transition-transform duration-fast group-focus-within/field:text-link" aria-hidden="true" />
    </x-slot:suffix>
</x-forms._field-wrapper>
