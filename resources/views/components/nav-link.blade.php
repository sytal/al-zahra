@props(['active' => false])

<a {{ $attributes->class([
    'relative inline-flex min-h-11 items-center px-3 text-sm font-medium transition duration-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary rounded-full',
    'after:pointer-events-none after:absolute after:inset-x-3 after:bottom-1.5 after:h-0.5 after:origin-center after:scale-x-0 after:rounded-full after:bg-brand-secondary after:transition-transform after:duration-base after:ease-enter hover:after:scale-x-100 focus-visible:after:scale-x-100',
    'text-strong after:scale-x-100' => $active,
    'text-body/80 hover:text-strong' => ! $active,
]) }} @if ($active) aria-current="page" @endif>
    {{ $slot }}
</a>
