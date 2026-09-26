{{-- Props: label (optional text between the rules), stars 1|3. Decorative unless labelled. --}}
@props(['label' => null, 'stars' => 1])

<div {{ $attributes->merge(['class' => 'star-divider']) }} @if (!$label) role="separator" aria-hidden="true" @endif>
    @if ($label)
        <span class="min-w-0 text-center text-sm font-semibold text-secondary-text">{{ $label }}</span>
    @elseif ($stars >= 3)
        <span class="star-mark text-[0.5rem] opacity-60" aria-hidden="true"></span>
        <span class="star-mark" aria-hidden="true"></span>
        <span class="star-mark text-[0.5rem] opacity-60" aria-hidden="true"></span>
    @else
        <span class="star-mark" aria-hidden="true"></span>
    @endif
</div>
