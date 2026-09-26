{{-- Props: items = [['label' => string, 'url' => ?string], ...]; last item is the current page. --}}
@props(['items' => []])

<nav aria-label="{{ __('kit_sections.breadcrumb') }}" {{ $attributes->merge(['class' => 'mb-4 min-w-0 text-sm text-muted']) }}>
    <ol class="flex min-w-0 flex-wrap items-center gap-x-2 gap-y-1">
        @foreach ($items as $item)
            @php $isLast = $loop->last; @endphp
            <li class="flex min-w-0 items-center gap-2 {{ $isLast ? 'max-w-full' : '' }}">
                @if (!$loop->first)<span class="star-mark text-[0.5rem] opacity-70" aria-hidden="true"></span>@endif
                @if (!empty($item['url']) && !$isLast)
                    <a href="{{ $item['url'] }}" wire:navigate class="focus-ring link-underline whitespace-nowrap py-1 text-body [@media(hover:hover)]:hover:text-brand-primary">{{ $item['label'] }}</a>
                @else
                    <span class="block max-w-[14rem] truncate font-medium text-strong sm:max-w-md" @if ($isLast) aria-current="page" @endif title="{{ $item['label'] }}">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
