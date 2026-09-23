@php
$locales = ['en' => 'English', 'ur' => 'اردو', 'hi' => 'हिन्दी', 'fa' => 'فارسی', 'ur-roman' => 'Roman Urdu'];
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
                href="{{ url()->to('/' . $code . '/' . request()->path()) }}"
                wire:navigate
                class="block px-4 py-2 text-sm text-ink hover:bg-surface"
            >
                {{ $label }}
            </a>
        @endforeach
    </x-slot>
</x-dropdown>
