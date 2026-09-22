@props(['items' => []])

<nav aria-label="Breadcrumb" class="mb-4 text-sm text-ink/60">
    <ol class="flex flex-wrap items-center gap-1.5">
        @foreach ($items as $item)
            <li class="flex items-center gap-1.5">
                @if (!$loop->first)<x-icon name="chevron-right" class="size-3.5 rtl:rotate-180" />@endif
                @if (!empty($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}" wire:navigate class="hover:text-brand-primary">{{ $item['label'] }}</a>
                @else
                    <span class="text-ink">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
