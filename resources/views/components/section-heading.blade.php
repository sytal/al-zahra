{{-- Props: eyebrow, title, lead, align start|center, as h1..h6, url + linkLabel (optional "view all" link). Default slot is extra content under the lead. --}}
@props(['eyebrow' => null, 'title', 'lead' => null, 'align' => 'start', 'as' => 'h2', 'url' => null, 'linkLabel' => null])

@php $center = $align === 'center'; @endphp

<header {{ $attributes->merge(['class' => 'mb-8 flex flex-col gap-4 sm:mb-10 ' . ($center ? 'items-center text-center' : 'md:flex-row md:items-end md:justify-between')]) }}>
    <div class="min-w-0 {{ $center ? 'mx-auto max-w-2xl' : 'max-w-2xl' }}">
        @if ($eyebrow)<p class="eyebrow mb-3">{{ $eyebrow }}</p>@endif
        <{{ $as }} class="heading-2 break-words">{{ $title }}</{{ $as }}>
        @if ($lead)<p class="lead mt-3">{{ $lead }}</p>@endif
        {{ $slot }}
    </div>
    @if ($url && $linkLabel)
        <a href="{{ $url }}" wire:navigate class="focus-ring link-underline group inline-flex min-h-11 shrink-0 items-center gap-2 self-start font-semibold text-brand-primary md:self-auto">
            <span>{{ $linkLabel }}</span>
            <x-icon name="arrow-right" class="size-4 transition-transform duration-base ease-enter rtl:-scale-x-100 [@media(hover:hover)]:group-hover:translate-x-1 rtl:[@media(hover:hover)]:group-hover:-translate-x-1" aria-hidden="true" />
        </a>
    @endif
</header>
