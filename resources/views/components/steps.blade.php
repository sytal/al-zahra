{{-- Props: items = [['title' => str, 'text' => str, 'icon' => ?heroicon], ...], layout auto|vertical. Vertical timeline on mobile, horizontal row from lg (up to 4 items per row). --}}
@props(['items' => [], 'layout' => 'auto'])

@php $horizontal = $layout === 'auto'; @endphp

<ol {{ $attributes->merge(['class' => 'relative grid min-w-0 gap-8 ' . ($horizontal ? (count($items) >= 4 ? 'lg:grid-cols-4' : (count($items) === 3 ? 'lg:grid-cols-3' : 'lg:grid-cols-2')) . ' lg:gap-6' : '')]) }}>
    @foreach ($items as $i => $item)
        <li class="relative flex min-w-0 gap-4 {{ $horizontal ? 'lg:flex-col lg:gap-5' : '' }}">
            @if (!$loop->last)
                <span class="absolute start-6 top-14 -bottom-8 w-px bg-gradient-to-b from-[var(--color-brand-secondary)] to-transparent {{ $horizontal ? 'lg:hidden' : '' }}" aria-hidden="true"></span>
            @endif
            @if ($horizontal && !$loop->last)
                <span class="absolute start-14 top-6 hidden h-px w-[calc(100%-2rem)] bg-gradient-to-r from-[var(--color-brand-secondary)] to-transparent rtl:bg-gradient-to-l lg:block" aria-hidden="true"></span>
            @endif
            <span class="relative flex size-12 shrink-0 items-center justify-center rounded-full border-2 border-brand-secondary bg-surface-raised font-display text-xl font-bold text-brand-primary shadow-soft">
                @if (!empty($item['icon']))<x-icon :name="$item['icon']" class="size-6" aria-hidden="true" />@else{{ $i + 1 }}@endif
                <span class="star-mark absolute -end-1 -top-1 text-xs" aria-hidden="true"></span>
            </span>
            <div class="min-w-0 pb-2">
                <h3 class="heading-4 break-words">
                    <span class="sr-only">{{ __('kit_sections.step_n', ['n' => $i + 1]) }}: </span>{{ $item['title'] }}
                </h3>
                @if (!empty($item['text']))<p class="mt-1.5 text-body">{{ $item['text'] }}</p>@endif
            </div>
        </li>
    @endforeach
</ol>
