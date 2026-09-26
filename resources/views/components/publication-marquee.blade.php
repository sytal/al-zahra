{{-- Props: items = [['title' => str, 'meta' => ?str (journal, year), 'url' => ?str], ...], label (accessible name), speed (seconds per loop, default scales with count). Fewer than 4 items render as a static wrap. Pauses on hover/focus; static and scrollable under reduced motion. The duplicated half is aria-hidden and unfocusable. --}}
@props(['items' => [], 'label' => null, 'speed' => null])

@php
$items = array_values($items);
$animate = count($items) >= 4;
$duration = $speed ?? max(24, count($items) * 7);
$copies = $animate ? [false, true] : [false];
@endphp

@if (count($items))
    <div {{ $attributes->merge(['class' => $animate ? 'marquee' : '']) }} role="region" aria-label="{{ $label ?? __('kit_sections.publications') }}" @if ($animate) tabindex="0" @endif>
        <div class="{{ $animate ? 'marquee-track' : 'flex flex-wrap justify-center gap-4' }}" @if ($animate) style="--marquee-duration: {{ $duration }}s; --marquee-gap: 1rem" @endif>
            @foreach ($copies as $hidden)
                @foreach ($items as $item)
                    @php $url = $item['url'] ?? null; @endphp
                    <div class="card-surface group relative flex w-[17rem] shrink-0 items-start gap-3 p-4 sm:w-80" @if ($hidden) aria-hidden="true" @endif>
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-tint text-brand-primary"><x-icon name="document-text" class="size-5" aria-hidden="true" /></span>
                        <div class="min-w-0">
                            @if ($url)
                                <a href="{{ $url }}" @if ($hidden) tabindex="-1" @endif class="focus-ring line-clamp-2 break-words text-sm font-semibold leading-snug text-strong after:absolute after:inset-0 [@media(hover:hover)]:hover:text-brand-primary">{{ $item['title'] }}</a>
                            @else
                                <p class="line-clamp-2 break-words text-sm font-semibold leading-snug text-strong">{{ $item['title'] }}</p>
                            @endif
                            @if (!empty($item['meta']))<p class="mt-1 truncate text-xs text-muted">{{ $item['meta'] }}</p>@endif
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
@endif
