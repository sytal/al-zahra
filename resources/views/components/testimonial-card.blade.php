{{-- Props: quote, name, role, avatar (url), rating 1-5 (optional), demo bool (when true a "Demo" marker is shown; never present placeholder testimonials as real). --}}
@props(['quote', 'name' => null, 'role' => null, 'avatar' => null, 'rating' => null, 'demo' => false])

<figure {{ $attributes->merge(['class' => 'card-surface card-hover relative flex h-full min-w-0 flex-col gap-4 overflow-hidden p-6 sm:p-7']) }}>
    <span class="numeral-display pointer-events-none absolute -top-2 end-4 select-none !text-[5rem] opacity-[0.14]" aria-hidden="true">&ldquo;</span>
    @if ($demo)
        <x-badge color="warning" variant="outline" size="sm" icon="beaker" class="self-start" :text="__('kit_sections.demo')" />
    @endif
    @if ($rating)
        <p class="flex gap-0.5 text-secondary-text" role="img" aria-label="{{ __('kit_sections.rating', ['n' => (int) $rating]) }}">
            @for ($s = 1; $s <= 5; $s++)<span class="star-mark text-base {{ $s <= (int) $rating ? '' : 'opacity-25' }}" aria-hidden="true"></span>@endfor
        </p>
    @endif
    <blockquote class="relative flex-1 break-words font-display text-lg leading-relaxed text-strong">{{ $quote }}</blockquote>
    @if ($name)
        <figcaption class="flex items-center gap-3 border-t border-dashed border-strong pt-4">
            <img src="{{ $avatar ?: asset('images/avatar-placeholder.svg') }}" alt="" width="44" height="44" loading="lazy" decoding="async" class="size-11 shrink-0 rounded-full object-cover ring-2 ring-[var(--color-brand-secondary)]">
            <span class="min-w-0"><span class="block truncate text-sm font-semibold text-strong">{{ $name }}</span>@if ($role)<span class="block truncate text-xs text-muted">{{ $role }}</span>@endif</span>
        </figcaption>
    @endif
</figure>
