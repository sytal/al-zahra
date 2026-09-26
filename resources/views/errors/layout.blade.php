<x-layouts.minimal>
    <x-slot name="seo"><title>{{ $title }} | {{ config('app.name') }}</title><meta name="robots" content="noindex"></x-slot>
    <div class="flex w-full max-w-md flex-col items-center gap-4 text-center">
        <span class="flex size-16 items-center justify-center rounded-full bg-brand-primary/10 text-brand-primary">
            <x-icon :name="$icon" class="size-8" />
        </span>
        <p class="text-sm font-medium tracking-widest text-ink/50">{{ $code }}</p>
        <h1 class="text-2xl font-semibold text-ink">{{ $title }}</h1>
        <p class="text-ink/70">{{ $message }}</p>
        <div class="mt-2 flex flex-wrap justify-center gap-3">
            @if (!empty($refresh))
                <button type="button" onclick="window.location.reload()" class="rounded-lg bg-brand-primary px-4 py-2 text-sm font-medium text-white transition duration-200 ease-in-out hover:opacity-90">{{ __('errors.refresh') }}</button>
                <a href="{{ url('/'.app()->getLocale()) }}" class="rounded-lg border border-ink/20 px-4 py-2 text-sm font-medium text-ink transition duration-200 ease-in-out hover:bg-ink/5">{{ __('errors.home') }}</a>
            @else
                <a href="{{ url('/'.app()->getLocale()) }}" class="rounded-lg bg-brand-primary px-4 py-2 text-sm font-medium text-white transition duration-200 ease-in-out hover:opacity-90">{{ __('errors.home') }}</a>
            @endif
        </div>
    </div>
</x-layouts.minimal>
