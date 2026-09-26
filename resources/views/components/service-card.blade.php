{{-- Props: icon, title, text, url, cta (link label), number ("01"), featured bool (gold gradient border for the primary service). Cursor spotlight on pointer devices. --}}
@props(['icon' => 'sparkles', 'title', 'text' => null, 'url' => null, 'cta' => null, 'number' => null, 'featured' => false])

<div {{ $attributes->merge(['class' => 'spotlight card-hover group relative flex h-full min-w-0 flex-col gap-5 overflow-hidden p-6 sm:p-7 ' . ($featured ? 'gradient-border-gold shadow-lift' : 'card-surface')]) }} x-data="spotlight">
    @if ($number)<span class="numeral-display pointer-events-none absolute top-2 end-4 select-none !text-[4rem] opacity-[0.12]" aria-hidden="true">{{ $number }}</span>@endif
    <span class="relative flex size-14 items-center justify-center rounded-2xl {{ $featured ? 'bg-gold-gradient text-on-secondary' : 'bg-tint text-brand-primary' }}">
        <x-icon :name="$icon" class="size-7" aria-hidden="true" />
    </span>
    <div class="min-w-0 flex-1">
        <h3 class="heading-3 break-words">{{ $title }}</h3>
        @if ($text)<p class="mt-2 text-body">{{ $text }}</p>@endif
    </div>
    @if ($url && $cta)
        <a href="{{ $url }}" wire:navigate class="focus-ring inline-flex min-h-11 items-center gap-2 font-semibold text-brand-primary after:absolute after:inset-0">
            <span class="link-underline">{{ $cta }}</span>
            <x-icon name="arrow-right" class="size-4 transition-transform duration-base ease-enter rtl:-scale-x-100 [@media(hover:hover)]:group-hover:translate-x-1 rtl:[@media(hover:hover)]:group-hover:-translate-x-1" aria-hidden="true" />
        </a>
    @endif
</div>
