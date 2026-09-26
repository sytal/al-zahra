@props([
    'name',
    'label' => null,
    'type' => 'text',
    'hint' => null,
    'id' => null,
    'icon' => null,
    'suffixIcon' => null,
    'required' => false,
    'optional' => false,
    'error' => null,
    'success' => false,
    'size' => 'md',
    'counter' => null,
    'revealable' => null,
    'hideLabel' => false,
])

@php
$fid = $id ?? str_replace(['.', '[', ']'], '-', (string) $name);
$message = $error ?? ($errors->has($name) ? $errors->first($name) : null);
$isPassword = $type === 'password';
$canReveal = $revealable ?? $isPassword;
$canReveal = $canReveal && $isPassword;
$max = $counter === true ? $attributes->get('maxlength') : $counter;
$defaults = [
    'email' => ['autocomplete' => 'email', 'inputmode' => 'email'],
    'tel' => ['autocomplete' => 'tel', 'inputmode' => 'tel'],
    'url' => ['inputmode' => 'url'],
    'search' => ['inputmode' => 'search', 'enterkeyhint' => 'search'],
][$type] ?? [];
if ($isPassword) {
    $defaults['autocomplete'] = $name === 'current_password' ? 'current-password' : 'new-password';
}
$describedBy = trim(($attributes->has('aria-describedby') ? $attributes->get('aria-describedby') . ' ' : '') . ($hint ? $fid . '-hint ' : '') . ($message ? $fid . '-error' : ''));
$hasPrefix = (bool) $icon;
@endphp

<x-forms._field-wrapper
    :name="$name" :id="$fid" :label="$label" :hint="$hint" :required="$required" :optional="$optional"
    :error="$message" :success="$success" :size="$size" :hide-label="$hideLabel" :prefix-icon="$icon" :counter="$max"
    :disabled="(bool) $attributes->get('disabled')" :readonly="(bool) $attributes->get('readonly')"
    :class="$attributes->get('class')"
>
    <input
        type="{{ $type }}"
        id="{{ $fid }}"
        name="{{ $name }}"
        data-control
        @if ($message) aria-invalid="true" @endif
        @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
        @if ($required) aria-required="true" @endif
        @if ($canReveal) x-bind:type="show ? 'text' : 'password'" @endif
        {{ $attributes->except(['class', 'aria-describedby'])->merge($defaults)->class([
            'peer block w-full min-w-0 flex-1 self-stretch rounded-xl border-0 bg-transparent py-2.5 text-base text-strong placeholder:text-subtle',
            'focus:border-0 focus:shadow-none focus:outline-none focus:ring-0 disabled:cursor-not-allowed read-only:cursor-default',
            $hasPrefix ? 'ps-2.5' : 'ps-3.5',
            ($canReveal || $suffixIcon || isset($suffix)) ? 'pe-1' : 'pe-3.5',
            '[&::-webkit-search-cancel-button]:hidden [&::-webkit-search-decoration]:hidden',
        ]) }}
    />

    <x-slot:suffix>
        @if ($suffixIcon)
            <x-icon :name="$suffixIcon" size="size-5" class="pointer-events-none me-3.5 shrink-0 text-muted" aria-hidden="true" />
        @endif
        @isset($suffix)
            {{ $suffix }}
        @endisset
        @if ($canReveal)
            <button
                type="button"
                class="tap-target me-1 shrink-0 rounded-lg text-muted outline-none transition-colors duration-fast focus-visible:ring-2 focus-visible:ring-brand active:scale-95 [@media(hover:hover)]:hover:text-strong"
                x-on:click="show = !show"
                x-bind:aria-pressed="show.toString()"
                x-bind:aria-label="show ? @js(__('kit_forms.hide_password')) : @js(__('kit_forms.show_password'))"
                aria-label="{{ __('kit_forms.show_password') }}"
                aria-controls="{{ $fid }}"
            >
                <x-icon name="eye" size="size-5" x-show="!show" aria-hidden="true" />
                <x-icon name="eye-slash" size="size-5" x-show="show" x-cloak aria-hidden="true" />
            </button>
        @endif
    </x-slot:suffix>
</x-forms._field-wrapper>
