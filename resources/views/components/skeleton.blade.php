@props(['variant' => 'text', 'lines' => 3, 'label' => null])

@php
$widths = ['w-full', 'w-11/12', 'w-4/5', 'w-2/3', 'w-3/4'];
@endphp

<div role="status" aria-busy="true" {{ $attributes->merge(['class' => 'min-w-0']) }}>
    <span class="sr-only">{{ $label ?? __('kit_forms.loading') }}</span>

    @switch($variant)
        @case('title')
            <div class="skeleton h-8 w-2/3 max-w-md"></div>
            @break

        @case('avatar')
            <div class="skeleton size-11 rounded-full"></div>
            @break

        @case('image')
            <div class="skeleton aspect-[16/10] w-full rounded-2xl"></div>
            @break

        @case('button')
            <div class="skeleton h-11 w-32 rounded-xl"></div>
            @break

        @case('card')
            <div class="overflow-hidden rounded-card border border-subtle bg-surface-raised">
                <div class="skeleton aspect-[16/10] w-full rounded-none"></div>
                <div class="space-y-3 p-5">
                    <div class="skeleton h-5 w-3/4"></div>
                    @for ($i = 0; $i < 2; $i++)
                        <div class="skeleton h-3.5 {{ $widths[$i + 1] }}"></div>
                    @endfor
                    <div class="flex items-center gap-3 pt-2">
                        <div class="skeleton size-8 rounded-full"></div>
                        <div class="skeleton h-3.5 w-24"></div>
                    </div>
                </div>
            </div>
            @break

        @case('row')
            <div class="flex items-center gap-4">
                <div class="skeleton size-11 shrink-0 rounded-full"></div>
                <div class="min-w-0 flex-1 space-y-2">
                    <div class="skeleton h-4 w-1/2"></div>
                    <div class="skeleton h-3.5 w-3/4"></div>
                </div>
            </div>
            @break

        @default
            <div class="space-y-2.5">
                @for ($i = 0; $i < (int) $lines; $i++)
                    <div class="skeleton h-4 {{ $i === (int) $lines - 1 && $lines > 1 ? 'w-2/3' : $widths[$i % 3] }}"></div>
                @endfor
            </div>
    @endswitch
</div>
