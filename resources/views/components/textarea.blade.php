@props([
    'name',
    'label' => null,
    'rows' => 4,
    'hint' => null,
    'id' => null,
    'required' => false,
    'optional' => false,
    'error' => null,
    'success' => false,
    'counter' => null,
    'autosize' => false,
    'hideLabel' => false,
])

@php
$fid = $id ?? str_replace(['.', '[', ']'], '-', (string) $name);
$message = $error ?? ($errors->has($name) ? $errors->first($name) : null);
$max = $counter === true ? $attributes->get('maxlength') : $counter;
$describedBy = trim(($attributes->has('aria-describedby') ? $attributes->get('aria-describedby') . ' ' : '') . ($hint ? $fid . '-hint ' : '') . ($message ? $fid . '-error' : ''));
@endphp

<x-forms._field-wrapper
    :name="$name" :id="$fid" :label="$label" :hint="$hint" :required="$required" :optional="$optional"
    :error="$message" :success="$success" :hide-label="$hideLabel" :counter="$max" :multiline="true"
    :disabled="(bool) $attributes->get('disabled')" :readonly="(bool) $attributes->get('readonly')"
    :class="$attributes->get('class')"
>
    <textarea
        id="{{ $fid }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        data-control
        @if ($message) aria-invalid="true" @endif
        @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
        @if ($required) aria-required="true" @endif
        @if ($autosize) x-data x-init="$nextTick(() => { $el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px' })" x-on:input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'" @endif
        {{ $attributes->except(['class', 'aria-describedby'])->class([
            'block w-full min-w-0 rounded-xl border-0 bg-transparent px-3.5 py-2.5 text-base text-strong placeholder:text-subtle',
            'focus:border-0 focus:shadow-none focus:outline-none focus:ring-0 disabled:cursor-not-allowed read-only:cursor-default',
            $autosize ? 'resize-none overflow-hidden' : 'resize-y',
        ]) }}
    >{{ $slot }}</textarea>
</x-forms._field-wrapper>
