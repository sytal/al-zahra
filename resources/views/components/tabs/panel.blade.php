@props(['id'])

<div
    x-bind="panel(@js((string) $id))"
    tabindex="0"
    x-transition:enter="transition duration-base ease-enter"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    {{ $attributes->merge(['class' => 'rounded-xl outline-none focus-visible:ring-4 focus-visible:ring-brand/30']) }}
>
    {{ $slot }}
</div>
