@props(['padding' => 'p-5', 'hoverable' => false])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-ink/10 bg-white dark:bg-surface ' . $padding . ' ' . ($hoverable ? 'transition duration-200 ease-in-out hover:shadow-lg hover:-translate-y-0.5' : '')]) }}>
    {{ $slot }}
</div>
