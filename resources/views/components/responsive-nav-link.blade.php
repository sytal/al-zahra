@props(['active' => false])

<a {{ $attributes->class([
    'group flex min-h-12 w-full items-center gap-3 rounded-xl px-3 text-start text-base font-medium transition duration-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary',
    'bg-tint text-strong' => $active,
    'text-body/80 hover:bg-tint hover:text-strong' => ! $active,
]) }} @if ($active) aria-current="page" @endif>
    {{ $slot }}
</a>
