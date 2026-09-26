@props(['items' => []])

<nav aria-label="Breadcrumb" class="mb-4 text-sm text-ink/70">
    <ol class="flex flex-wrap items-center gap-1.5">
        @foreach ($items as $item)
            <li class="flex min-w-0 items-center gap-1.5">
                @if (!$loop->first)<x-icon name="chevron-right" class="size-3.5 rtl:rotate-180" />@endif
                @if (!empty($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}" wire:navigate class="hover:text-brand-primary whitespace-nowrap">{{ $item['label'] }}</a>
                @else
                    <span class="truncate text-ink">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
