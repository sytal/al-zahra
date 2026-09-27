@props(['title', 'eyebrow' => null, 'description' => null])

<header {{ $attributes->class(['relative flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between']) }}>
    <div class="min-w-0 space-y-2">
        @if ($eyebrow)
            <p class="eyebrow inline-flex items-center gap-2 text-secondary-text"><span class="star-mark text-sm" aria-hidden="true"></span>{{ $eyebrow }}</p>
        @endif
        <h1 class="heading-2 break-words text-strong">{{ $title }}</h1>
        @if ($description)
            <p class="max-w-2xl text-body">{{ $description }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="flex shrink-0 flex-wrap items-center gap-2">{{ $actions }}</div>
    @endisset
</header>
