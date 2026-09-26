@php
$locales = ['en' => 'English', 'ur' => 'اردو', 'hi' => 'हिन्दी', 'fa' => 'فارسی', 'ur-roman' => 'Roman Urdu'];

$segments = explode('/', trim(request()->path(), '/'));
if (in_array($segments[0] ?? null, config('app.locales'), true)) {
    array_shift($segments);
}
$pathWithoutLocale = implode('/', $segments);
@endphp

<x-dropdown>
    <x-slot name="trigger">
        <button type="button" aria-haspopup="true" x-bind:aria-expanded="open.toString()" class="flex min-h-10 items-center gap-1 rounded-lg px-2 text-sm text-ink transition duration-200 ease-in-out hover:text-brand-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">
            <x-icon name="language" class="size-4" />
            {{ $locales[app()->getLocale()] ?? app()->getLocale() }}
        </button>
    </x-slot>

    <x-slot name="content">
        @foreach ($locales as $code => $label)
            <a
                href="{{ url('/' . $code . ($pathWithoutLocale ? '/' . $pathWithoutLocale : '')) }}"
                wire:navigate
                @if (app()->getLocale() === $code) aria-current="true" @endif class="block px-4 py-2.5 text-sm text-ink hover:bg-ink/5 focus-visible:bg-ink/5 focus-visible:outline-none {{ app()->getLocale() === $code ? 'font-semibold text-brand-primary' : '' }}"
            >
                {{ $label }}
            </a>
        @endforeach
    </x-slot>
</x-dropdown>
