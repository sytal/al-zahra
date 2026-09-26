@props(['variant' => 'dropdown', 'direction' => 'down'])

@php
$locales = ['en' => 'English', 'ur' => 'اردو', 'hi' => 'हिन्दी', 'fa' => 'فارسی', 'ur-roman' => 'Roman Urdu'];
$current = app()->getLocale();

$segments = explode('/', trim(request()->path(), '/'));
if (in_array($segments[0] ?? null, config('app.locales'), true)) {
    array_shift($segments);
}
$pathWithoutLocale = implode('/', $segments);
$query = request()->getQueryString();
$hrefFor = fn (string $code) => url('/' . $code . ($pathWithoutLocale ? '/' . $pathWithoutLocale : '')) . ($query ? '?' . $query : '');
@endphp

@if ($variant === 'list')
    <nav aria-label="{{ __('shell_public.language') }}" {{ $attributes->class(['flex flex-wrap gap-2']) }}>
        @foreach ($locales as $code => $label)
            <a
                href="{{ $hrefFor($code) }}"
                wire:navigate
                lang="{{ $code === 'ur-roman' ? 'ur-Latn' : $code }}"
                @if ($current === $code) aria-current="true" @endif
                class="inline-flex min-h-11 items-center rounded-full border px-4 text-sm font-medium transition duration-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary active:scale-[0.97] {{ $current === $code ? 'border-brand-secondary bg-brand-secondary/15 text-strong' : 'border-subtle text-body/80 hover:border-strong hover:text-strong' }}"
            >{{ $label }}</a>
        @endforeach
    </nav>
@else
    <div x-data="{ open: false }" x-on:click.outside="open = false" x-on:keydown.escape="open = false" {{ $attributes->class(['relative']) }}>
        <button
            type="button"
            x-on:click="open = !open"
            aria-haspopup="true"
            x-bind:aria-expanded="open.toString()"
            class="tap-target gap-1.5 rounded-full px-3 text-sm font-medium text-body/80 transition duration-200 hover:bg-tint hover:text-strong focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary active:scale-[0.97]"
        >
            <x-icon name="language" class="size-4" />
            <span>{{ $locales[$current] ?? $current }}</span>
            <x-icon name="chevron-down" class="size-3.5 transition-transform duration-200" x-bind:class="open ? 'rotate-180' : ''" />
        </button>

        <div
            x-show="open"
            x-cloak
            x-transition:enter="transition duration-150 ease-out"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition duration-100 ease-in"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute end-0 z-dropdown w-52 rounded-2xl border bg-surface-raised p-1.5 shadow-float {{ $direction === 'up' ? 'bottom-full mb-2 origin-bottom' : 'top-full mt-2 origin-top' }}"
        >
            @foreach ($locales as $code => $label)
                <a
                    href="{{ $hrefFor($code) }}"
                    wire:navigate
                    lang="{{ $code === 'ur-roman' ? 'ur-Latn' : $code }}"
                    x-on:click="open = false"
                    @if ($current === $code) aria-current="true" @endif
                    class="flex min-h-11 items-center justify-between gap-3 rounded-xl px-3 text-sm transition duration-150 hover:bg-tint focus-visible:bg-tint focus-visible:outline-none {{ $current === $code ? 'font-semibold text-strong' : 'text-body' }}"
                >
                    {{ $label }}
                    @if ($current === $code)<x-icon name="check" class="size-4 text-brand-primary" />@endif
                </a>
            @endforeach
        </div>
    </div>
@endif
