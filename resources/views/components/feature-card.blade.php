{{-- Props: icon (heroicon), title, text (or slot), url (makes the whole card a link), as h3|h4. --}}
@props(['icon' => 'sparkles', 'title', 'text' => null, 'url' => null, 'as' => 'h3'])

<div {{ $attributes->merge(['class' => 'card-surface card-hover group relative flex h-full min-w-0 flex-col gap-4 p-5 sm:p-6']) }}>
    <span class="relative flex size-14 shrink-0 items-center justify-center rounded-2xl bg-brand-gradient text-on-brand shadow-soft transition-transform duration-base ease-spring [@media(hover:hover)]:group-hover:-rotate-6">
        <x-icon :name="$icon" class="size-7" aria-hidden="true" />
        <span class="star-mark absolute -end-2 -top-2 text-base" aria-hidden="true"></span>
    </span>
    <div class="min-w-0">
        <{{ $as }} class="heading-4 break-words">
            @if ($url)<a href="{{ $url }}" wire:navigate class="focus-ring after:absolute after:inset-0">{{ $title }}</a>@else{{ $title }}@endif
        </{{ $as }}>
        @if ($text)<p class="mt-2 text-body">{{ $text }}</p>@endif
        {{ $slot }}
    </div>
</div>
