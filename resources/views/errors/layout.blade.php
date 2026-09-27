@php
$locale = app()->getLocale();
$illustrations = [403 => 'illus-403', 404 => 'illus-404', 419 => 'illus-419'];
$illustration = $illustrations[$code] ?? ($code >= 500 ? 'illus-500' : 'illus-403');
$btnPrimary = 'focus-ring inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-on-brand shadow-soft transition duration-fast hover:bg-brand-primary/90 active:scale-[0.98]';
$btnGhost = 'focus-ring inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-strong bg-surface-raised px-5 py-2.5 text-sm font-semibold text-strong transition duration-fast hover:bg-tint active:scale-[0.98]';
@endphp
<x-layouts.minimal :centered="true">
    <x-slot name="seo"><title>{{ $title }} | {{ config('app.name') }}</title><meta name="robots" content="noindex"></x-slot>

    <section class="relative w-full max-w-4xl" aria-labelledby="error-title">
        <div class="card-surface relative overflow-hidden p-6 sm:p-10 lg:p-14">
            <div class="glow-gold -bottom-24 -end-24" style="--glow-size: 20rem" aria-hidden="true"></div>

            <div class="relative grid items-center gap-8 md:grid-cols-2 md:gap-12 [@media(max-height:480px)]:grid-cols-[1fr_9rem] [@media(max-height:480px)]:gap-6">
                <div class="order-2 min-w-0 space-y-5 text-center md:order-1 md:text-start [@media(max-height:480px)]:order-1 [@media(max-height:480px)]:text-start">
                    <p class="eyebrow inline-flex items-center gap-2 rounded-full bg-tint px-3 py-1 text-brand-primary">
                        <span class="star-mark text-sm" aria-hidden="true"></span>{{ __('errors.code_label', ['code' => $code]) }}
                    </p>
                    <h1 id="error-title" class="heading-2 break-words text-strong">{{ $title }}</h1>
                    <p class="text-body">{{ $message }}</p>

                    <div class="flex flex-col gap-3 pt-1 xs:flex-row xs:flex-wrap xs:justify-center md:justify-start">
                        @if (!empty($refresh))
                            <button type="button" onclick="window.location.reload()" class="{{ $btnPrimary }}">
                                <x-icon name="arrow-path" class="size-5" />{{ __('errors.refresh') }}
                            </button>
                            <a href="{{ url('/'.$locale) }}" class="{{ $btnGhost }}">
                                <x-icon name="home" class="size-5" />{{ __('errors.home') }}
                            </a>
                        @else
                            <a href="{{ url('/'.$locale) }}" class="{{ $btnPrimary }}">
                                <x-icon name="home" class="size-5" />{{ __('errors.home') }}
                            </a>
                            <a href="{{ url('/'.$locale.'/contact') }}" class="{{ $btnGhost }}">
                                <x-icon name="chat-bubble-left-right" class="size-5" />{{ __('errors.contact') }}
                            </a>
                        @endif
                    </div>

                    @if ($code === 404)
                        <div class="space-y-2 pt-2">
                            <p class="text-sm text-muted">{{ __('errors.hint_404') }}</p>
                            <ul class="flex flex-wrap justify-center gap-2 md:justify-start">
                                @foreach (['articles' => 'articles', 'courses' => 'courses', 'research' => 'research'] as $path => $key)
                                    <li>
                                        <a href="{{ url('/'.$locale.'/'.$path) }}" class="focus-ring tap-target rounded-full border border-strong bg-tint px-4 text-sm font-medium text-strong transition duration-fast hover:bg-surface-sunken">{{ __('nav.'.$key) }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="order-1 mx-auto w-full max-w-[15rem] sm:max-w-xs md:order-2 md:max-w-none [@media(max-height:480px)]:order-2">
                    <img src="{{ asset('images/illustrations/'.$illustration.'.svg') }}" width="400" height="300" alt="" aria-hidden="true" class="h-auto w-full" decoding="async">
                </div>
            </div>
        </div>
    </section>
</x-layouts.minimal>
