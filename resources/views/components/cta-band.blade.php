{{-- Props: title, lead, primaryLabel + primaryUrl, secondaryLabel + secondaryUrl, eyebrow. Default slot renders under the buttons (e.g. newsletter form). Dark teal band, gold glow, star watermark. --}}
@props(['title', 'lead' => null, 'eyebrow' => null, 'primaryLabel' => null, 'primaryUrl' => null, 'secondaryLabel' => null, 'secondaryUrl' => null])

<div {{ $attributes->merge(['class' => 'bg-section-dark grain relative isolate overflow-hidden rounded-panel px-6 py-10 shadow-float sm:px-10 sm:py-14 lg:px-16']) }}>
    <span class="glow-gold -top-32 end-[-6rem] opacity-50" style="--glow-size: 22rem" aria-hidden="true"></span>
    <span class="star-mark pointer-events-none absolute -bottom-16 -start-10 z-0 text-[14rem] opacity-[0.12]" aria-hidden="true"></span>
    <span class="bg-pattern-islamic pointer-events-none absolute inset-0 -z-10" aria-hidden="true"></span>
    <div class="relative z-raised mx-auto flex max-w-2xl flex-col items-center gap-4 text-center">
        @if ($eyebrow)<p class="eyebrow">{{ $eyebrow }}</p>@endif
        <h2 class="heading-2 break-words">{{ $title }}</h2>
        @if ($lead)<p class="lead">{{ $lead }}</p>@endif
        @if ($primaryLabel || $secondaryLabel)
            <div class="mt-3 flex w-full flex-col items-stretch gap-3 xs:w-auto xs:flex-row xs:items-center">
                @if ($primaryLabel && $primaryUrl)
                    <a href="{{ $primaryUrl }}" wire:navigate class="focus-ring shimmer-sweep-auto inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-secondary px-7 py-3 text-base font-semibold text-on-secondary shadow-gold transition duration-fast ease-enter active:scale-[0.98] [@media(hover:hover)]:hover:-translate-y-0.5 [@media(hover:hover)]:hover:bg-brand-secondary-hover">
                        {{ $primaryLabel }}
                        <x-icon name="arrow-right" class="size-5 rtl:-scale-x-100" aria-hidden="true" />
                    </a>
                @endif
                @if ($secondaryLabel && $secondaryUrl)
                    <a href="{{ $secondaryUrl }}" wire:navigate class="focus-ring inline-flex min-h-12 items-center justify-center rounded-xl border border-strong px-7 py-3 text-base font-semibold text-strong transition duration-fast ease-enter active:scale-[0.98] [@media(hover:hover)]:hover:bg-white/10">{{ $secondaryLabel }}</a>
                @endif
            </div>
        @endif
        @if (trim((string) $slot) !== '')<div class="mt-3 w-full max-w-md">{{ $slot }}</div>@endif
    </div>
</div>
