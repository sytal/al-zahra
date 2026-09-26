{{-- Props: text (or slot), cite (person), role, variant pull|centered|card. --}}
@props(['text' => null, 'cite' => null, 'role' => null, 'variant' => 'pull'])

@if ($variant === 'centered')
    <figure {{ $attributes->merge(['class' => 'mx-auto max-w-3xl text-center']) }}>
        <span class="star-mark mx-auto mb-4 block text-2xl" aria-hidden="true"></span>
        <blockquote class="font-display text-2xl leading-snug text-strong text-balance sm:text-3xl">{{ $text ?? $slot }}</blockquote>
        @if ($cite)
            <figcaption class="mt-5 text-sm font-semibold text-body">{{ $cite }}@if ($role)<span class="block font-normal text-muted">{{ $role }}</span>@endif</figcaption>
        @endif
    </figure>
@elseif ($variant === 'card')
    <figure {{ $attributes->merge(['class' => 'card-surface relative overflow-hidden p-6 sm:p-8']) }}>
        <span class="numeral-display pointer-events-none absolute -top-2 end-4 select-none opacity-20" aria-hidden="true">&rdquo;</span>
        <blockquote class="relative font-display text-xl leading-snug text-strong text-balance">{{ $text ?? $slot }}</blockquote>
        @if ($cite)
            <figcaption class="relative mt-4 text-sm font-semibold text-body">{{ $cite }}@if ($role)<span class="block font-normal text-muted">{{ $role }}</span>@endif</figcaption>
        @endif
    </figure>
@else
    <figure {{ $attributes->merge(['class' => 'min-w-0']) }}>
        <blockquote class="pull-quote">
            {{ $text ?? $slot }}
            @if ($cite)<cite>{{ $cite }}@if ($role) <span class="font-normal text-muted">&middot; {{ $role }}</span>@endif</cite>@endif
        </blockquote>
    </figure>
@endif
