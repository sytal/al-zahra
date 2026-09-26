{{--
Props: variant home|banner|detail, title (h1), highlight (substring of title rendered in gradient), eyebrow, lead,
       image (detail cover url), imageAlt, imageWidth/imageHeight, illustration (file stem in public/images/illustrations, banner and home),
       align start|center (banner).
Slots: breadcrumbs, actions (CTA buttons), chips (trust chips, home), meta (badges/byline, detail/banner), visual (replaces the default home illustration), floating (glass cards over the visual, home).
--}}
@props(['variant' => 'banner', 'title', 'highlight' => null, 'eyebrow' => null, 'lead' => null, 'image' => null, 'imageAlt' => '', 'imageWidth' => 1200, 'imageHeight' => 630, 'illustration' => null, 'align' => 'start'])

@php
$safe = e($title);
if ($highlight && str_contains($title, $highlight)) {
    $safe = str_replace(e($highlight), '<span class="text-gradient-brand">' . e($highlight) . '</span>', $safe);
}
@endphp

@if ($variant === 'home')
    <section {{ $attributes->merge(['class' => 'relative isolate overflow-hidden bg-hero-gradient']) }}>
        <span class="glow-teal -top-24 start-[-8rem]" style="--glow-size: 32rem" aria-hidden="true"></span>
        <span class="glow-gold bottom-[-10rem] end-[-6rem] opacity-70" style="--glow-size: 30rem" aria-hidden="true"></span>
        <span class="light-rays" aria-hidden="true"></span>
        <span class="bg-pattern-neural pointer-events-none absolute inset-0 -z-10" aria-hidden="true"></span>
        <div class="container-page grid items-center gap-10 py-12 sm:py-16 lg:grid-cols-12 lg:gap-8 lg:py-24">
            <div class="min-w-0 lg:col-span-7">
                @if ($eyebrow)<p class="eyebrow mb-5">{{ $eyebrow }}</p>@endif
                <h1 class="heading-display break-words">{!! $safe !!}</h1>
                @if ($lead)<p class="lead mt-5 max-w-xl">{{ $lead }}</p>@endif
                @isset($actions)<div class="mt-8 flex flex-col gap-3 xs:flex-row xs:flex-wrap xs:items-center">{{ $actions }}</div>@endisset
                @isset($chips)<ul class="mt-8 flex flex-wrap gap-2 text-sm">{{ $chips }}</ul>@endisset
            </div>
            <div class="relative mx-auto w-full max-w-md min-w-0 lg:col-span-5 lg:max-w-none">
                @isset($visual)
                    {{ $visual }}
                @else
                    <span class="star-mark pointer-events-none absolute -end-6 -top-6 z-0 text-[9rem] opacity-20 motion-safe:animate-float sm:text-[12rem]" aria-hidden="true"></span>
                    <picture class="relative z-raised block">
                        <source media="(min-width: 420px)" srcset="{{ asset('images/illustrations/hero-neurolinguistics.svg') }}">
                        <img src="{{ asset('images/illustrations/hero-neurolinguistics-sm.svg') }}" alt="" width="600" height="480" fetchpriority="high" decoding="async" class="mx-auto h-auto w-full max-w-[34rem] drop-shadow-xl">
                    </picture>
                @endisset
                @isset($floating)<div class="relative z-raised mt-4 grid gap-3 xs:grid-cols-2 lg:absolute lg:inset-x-0 lg:-bottom-6 lg:mt-0">{{ $floating }}</div>@endisset
            </div>
        </div>
        <span class="gold-thread absolute inset-x-0 bottom-0" aria-hidden="true"></span>
    </section>
@elseif ($variant === 'detail')
    <section {{ $attributes->merge(['class' => 'relative isolate']) }}>
        <div class="relative isolate overflow-hidden bg-section-dark">
            <span class="light-rays" aria-hidden="true"></span>
            <div class="mx-auto aspect-[16/9] max-h-[34rem] w-full max-w-[90rem] sm:aspect-[21/9]">
                <img src="{{ $image ?: asset('images/article-placeholder.svg') }}" alt="{{ $imageAlt }}" width="{{ $imageWidth }}" height="{{ $imageHeight }}" fetchpriority="high" decoding="async"
                    onerror="this.onerror=null;this.src='{{ asset('images/article-placeholder.svg') }}'" class="size-full object-cover opacity-90">
            </div>
            <span class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/40 to-transparent" aria-hidden="true"></span>
        </div>
        <div class="container-page relative z-raised -mt-16 sm:-mt-24">
            <div class="card-surface mx-auto max-w-4xl !rounded-panel p-5 shadow-float sm:p-8 lg:p-10">
                @isset($breadcrumbs){{ $breadcrumbs }}@endisset
                @if ($eyebrow)<p class="eyebrow mb-3">{{ $eyebrow }}</p>@endif
                <h1 class="heading-1 break-words">{!! $safe !!}</h1>
                @if ($lead)<p class="lead mt-3">{{ $lead }}</p>@endif
                @isset($meta)<div class="mt-5 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-body">{{ $meta }}</div>@endisset
                @isset($actions)<div class="mt-6 flex flex-wrap gap-3">{{ $actions }}</div>@endisset
            </div>
        </div>
    </section>
@else
    @php $center = $align === 'center'; @endphp
    <section {{ $attributes->merge(['class' => 'relative isolate overflow-hidden border-b bg-hero-gradient']) }}>
        <span class="glow-teal -top-32 end-[-6rem] opacity-80" style="--glow-size: 24rem" aria-hidden="true"></span>
        <span class="bg-pattern-islamic pointer-events-none absolute inset-0 -z-10" aria-hidden="true"></span>
        <div class="container-page grid items-center gap-8 py-10 sm:py-14 lg:py-16 {{ $illustration && !$center ? 'md:grid-cols-[minmax(0,1fr)_minmax(0,18rem)] lg:grid-cols-[minmax(0,1fr)_minmax(0,22rem)]' : '' }}">
            <div class="min-w-0 {{ $center ? 'mx-auto max-w-3xl text-center' : 'max-w-3xl' }}">
                @isset($breadcrumbs){{ $breadcrumbs }}@endisset
                @if ($eyebrow)<p class="eyebrow mb-3">{{ $eyebrow }}</p>@endif
                <h1 class="heading-1 break-words">{!! $safe !!}</h1>
                @if ($lead)<p class="lead mt-4 {{ $center ? 'mx-auto' : '' }} max-w-2xl">{{ $lead }}</p>@endif
                @isset($meta)<div class="mt-5 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-body {{ $center ? 'justify-center' : '' }}">{{ $meta }}</div>@endisset
                @isset($actions)<div class="mt-6 flex flex-wrap gap-3 {{ $center ? 'justify-center' : '' }}">{{ $actions }}</div>@endisset
            </div>
            @if ($illustration && !$center)
                <div class="relative hidden md:block">
                    <span class="star-mark absolute -start-4 -top-4 z-0 text-7xl opacity-25" aria-hidden="true"></span>
                    <img src="{{ asset('images/illustrations/illus-' . $illustration . '.svg') }}" alt="" width="400" height="300" loading="lazy" decoding="async" class="relative h-auto w-full">
                </div>
            @endif
        </div>
        <span class="gold-thread absolute inset-x-0 bottom-0" aria-hidden="true"></span>
    </section>
@endif
