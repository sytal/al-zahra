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
        <button type="button" class="flex items-center gap-1 text-sm text-ink hover:text-brand-primary">
            <x-icon name="language" class="size-4" />
            {{ $locales[app()->getLocale()] ?? app()->getLocale() }}
        </button>
    </x-slot>

    <x-slot name="content">
        @foreach ($locales as $code => $label)
            <a
                href="{{ url('/' . $code . ($pathWithoutLocale ? '/' . $pathWithoutLocale : '')) }}"
                wire:navigate
                class="block px-4 py-2 text-sm text-ink hover:bg-surface"
            >
                {{ $label }}
            </a>
        @endforeach
    </x-slot>
</x-dropdown>
