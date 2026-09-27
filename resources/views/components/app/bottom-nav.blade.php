@props(['links'])

@php $locale = app()->getLocale(); @endphp

<nav aria-label="{{ __('shell_app.mobile_nav') }}" class="pb-safe ps-safe pe-safe fixed inset-x-0 bottom-0 z-header border-t border-subtle bg-surface-raised/95 shadow-float backdrop-blur md:hidden">
    <ul class="grid" style="grid-template-columns: repeat({{ count($links) }}, minmax(0, 1fr))">
        @foreach ($links as $link)
            @php $active = $link['active']; @endphp
            <li class="min-w-0">
                <a
                    href="{{ route($link['route'], $locale) }}"
                    wire:navigate
                    @if ($active) aria-current="page" @endif
                    title="{{ $link['label'] }}"
                    class="focus-ring relative flex min-h-14 flex-col items-center justify-center gap-0.5 px-1 py-1.5 text-[0.6875rem] font-medium leading-tight transition duration-fast active:scale-95 {{ $active ? 'text-brand-primary' : 'text-muted' }}"
                >
                    @if ($active)<span class="absolute inset-x-4 top-0 h-0.5 rounded-b-full bg-secondary" aria-hidden="true"></span>@endif
                    <x-icon :name="$link['icon']" class="size-6 shrink-0" />
                    <span class="block w-full truncate text-center">{{ $link['label'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
