@props(['centered' => false])

@php
$locale = app()->getLocale();
$trust = [
    ['icon' => 'academic-cap', 'title' => __('shell_app.trust_1_title'), 'text' => __('shell_app.trust_1_text')],
    ['icon' => 'lock-closed', 'title' => __('shell_app.trust_2_title'), 'text' => __('shell_app.trust_2_text')],
    ['icon' => 'language', 'title' => __('shell_app.trust_3_title'), 'text' => __('shell_app.trust_3_text')],
];
@endphp

<div class="relative isolate flex min-h-dvh-full flex-col {{ $centered ? '' : 'lg:flex-row' }}">
    @unless ($centered)
        <aside class="bg-section-dark relative hidden overflow-hidden lg:sticky lg:top-0 lg:flex lg:h-screen lg:w-[42%] lg:shrink-0 xl:w-[40%]" aria-label="{{ config('app.name') }}">
            <div class="bg-pattern-islamic absolute inset-0 -z-10" aria-hidden="true"></div>
            <div class="glow-gold -bottom-32 -end-32" style="--glow-size: 30rem" aria-hidden="true"></div>
            <div class="glow-teal -start-40 -top-40" style="--glow-size: 30rem" aria-hidden="true"></div>

            <div class="relative flex w-full flex-col justify-between gap-8 overflow-y-auto p-10 xl:p-14">
                <a href="{{ route('home', $locale) }}" wire:navigate class="focus-ring inline-flex w-fit items-center rounded-lg" aria-label="{{ config('app.name') }}">
                    <x-app.brand size="lg" />
                </a>

                <div class="space-y-8">
                    <div class="space-y-3">
                        <p class="eyebrow inline-flex items-center gap-2 text-secondary-text"><span class="star-mark text-sm" aria-hidden="true"></span>{{ __('shell_app.tagline') }}</p>
                        <h2 class="font-display text-3xl font-bold leading-tight text-strong xl:text-4xl">{{ __('shell_app.auth_headline') }}</h2>
                        <p class="max-w-md text-body">{{ __('shell_app.auth_sub') }}</p>
                    </div>

                    <img src="{{ asset('images/illustrations/illus-auth.svg') }}" width="400" height="300" alt="" aria-hidden="true" class="mx-auto h-[24vh] max-h-72 w-auto max-w-full [@media(max-height:700px)]:hidden" loading="eager" decoding="async">
                </div>

                <ul class="space-y-3.5">
                    @foreach ($trust as $point)
                        <li class="flex items-start gap-3">
                            <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-primary text-on-brand"><x-icon :name="$point['icon']" class="size-5" /></span>
                            <span class="min-w-0">
                                <span class="block font-semibold text-strong">{{ $point['title'] }}</span>
                                <span class="block text-sm text-body">{{ $point['text'] }}</span>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>
    @endunless

    <div class="relative isolate flex min-w-0 flex-1 flex-col overflow-hidden bg-page {{ $centered ? 'bg-hero-gradient' : '' }}">
        <div class="glow-teal -top-40 start-1/2 -translate-x-1/2 rtl:translate-x-1/2" style="--glow-size: 32rem" aria-hidden="true"></div>
        @if ($centered)
            <div class="bg-pattern-islamic absolute inset-0 -z-10" aria-hidden="true"></div>
        @endif

        <header class="pt-safe ps-safe pe-safe">
            <div class="flex items-center justify-between gap-3 px-4 py-3 sm:px-6 lg:px-10">
                <a href="{{ route('home', $locale) }}" wire:navigate class="focus-ring inline-flex items-center rounded-lg {{ $centered ? '' : 'lg:hidden' }}" aria-label="{{ config('app.name') }}">
                    <x-app.brand />
                </a>
                @unless ($centered)
                    <a href="{{ route('home', $locale) }}" wire:navigate class="link-underline hidden items-center gap-2 text-sm font-medium text-body transition duration-fast hover:text-strong lg:inline-flex">
                        <x-icon name="arrow-left" class="size-4 rtl:-scale-x-100" />
                        {{ __('shell_app.back_home') }}
                    </a>
                @endunless
                <div class="flex items-center gap-1" role="group" aria-label="{{ __('shell_app.controls') }}">
                    <x-theme-toggle />
                    <x-language-switcher />
                </div>
            </div>
        </header>

        <main id="main" tabindex="-1" class="flex flex-1 items-center justify-center px-4 py-8 focus:outline-none sm:px-6 sm:py-12 lg:px-10 {{ $centered ? 'text-body' : '' }}">
            <div class="flex w-full min-w-0 flex-col items-center gap-6">
                {{ $slot }}

                @unless ($centered)
                    <ul class="flex flex-wrap justify-center gap-2 lg:hidden" aria-label="{{ __('shell_app.tagline') }}">
                        @foreach ($trust as $point)
                            <li class="inline-flex items-center gap-1.5 rounded-full border border-strong bg-tint px-3 py-1.5 text-xs font-medium text-strong">
                                <x-icon :name="$point['icon']" class="size-4 text-brand-primary" />
                                {{ $point['title'] }}
                            </li>
                        @endforeach
                    </ul>
                @endunless
            </div>
        </main>

        <footer class="pb-safe ps-safe pe-safe">
            <p class="px-4 py-4 text-center text-xs text-muted sm:px-6">&copy; {{ now()->year }} {{ config('app.name') }}. {{ __('shell_app.rights') }}</p>
        </footer>
    </div>
</div>
