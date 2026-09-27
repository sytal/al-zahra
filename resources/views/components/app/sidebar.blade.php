@props(['links'])

@php $locale = app()->getLocale(); @endphp

<aside id="sidebar" class="sticky top-0 hidden h-screen shrink-0 flex-col border-e border-subtle bg-surface-raised transition-[width] duration-base ease-enter md:flex" x-bind:class="collapsed ? 'w-[4.75rem]' : 'w-64'">
    <div class="flex h-16 shrink-0 items-center border-b border-subtle px-4" x-bind:class="collapsed && 'justify-center px-0'">
        <a href="{{ route('home', $locale) }}" wire:navigate class="focus-ring inline-flex items-center rounded-lg" aria-label="{{ config('app.name') }}">
            <x-app.brand x-show="!collapsed" />
            <x-brand.logo variant="mark" class="size-9" x-show="collapsed" x-cloak />
        </a>
    </div>

    <nav aria-label="{{ __('polish_shell.dashboard_nav') }}" class="flex-1 overflow-y-auto overscroll-contain px-3 py-4">
        <ul class="space-y-1">
            @foreach ($links as $link)
                @php $active = $link['active']; @endphp
                <li>
                    <a
                        href="{{ route($link['route'], $locale) }}"
                        wire:navigate
                        @if ($active) aria-current="page" @endif
                        x-bind:title="collapsed ? @js($link['label']) : null"
                        class="focus-ring group relative flex min-h-11 items-center gap-3 rounded-xl px-3 text-sm font-medium transition duration-fast active:scale-[0.98] {{ $active ? 'bg-tint font-semibold text-brand-primary' : 'text-body hover:bg-tint hover:text-strong' }}"
                    >
                        @if ($active)<span class="absolute inset-y-2 start-0 w-1 rounded-e-full bg-secondary" aria-hidden="true"></span>@endif
                        <x-icon :name="$link['icon']" class="size-5 shrink-0" />
                        <span class="min-w-0 truncate" x-bind:class="collapsed && 'sr-only'">{{ $link['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    <div class="shrink-0 space-y-2 border-t border-subtle p-3">
        <a href="{{ route('home', $locale) }}" wire:navigate x-bind:title="collapsed ? @js(__('shell_app.view_site')) : null" class="focus-ring flex min-h-11 items-center gap-3 rounded-xl px-3 text-sm font-medium text-body transition duration-fast hover:bg-tint hover:text-strong">
            <x-icon name="globe-alt" class="size-5 shrink-0" />
            <span class="min-w-0 truncate" x-bind:class="collapsed && 'sr-only'">{{ __('shell_app.view_site') }}</span>
        </a>

        <div class="rounded-xl bg-tint p-2.5" x-bind:class="collapsed && 'flex justify-center p-1.5'">
            <x-app.user-card :user="auth()->user()" collapsible />
        </div>

        <form method="POST" action="{{ route('logout', $locale) }}">
            @csrf
            <button type="submit" x-bind:title="collapsed ? @js(__('dashboard.nav_logout')) : null" class="focus-ring flex min-h-11 w-full items-center gap-3 rounded-xl px-3 text-sm font-medium text-body transition duration-fast hover:bg-danger/10 hover:text-strong">
                <x-icon name="arrow-right-on-rectangle" class="size-5 shrink-0 rtl:-scale-x-100" />
                <span class="min-w-0 truncate" x-bind:class="collapsed && 'sr-only'">{{ __('dashboard.nav_logout') }}</span>
            </button>
        </form>

        <button type="button" x-on:click="toggleSidebar()" x-bind:aria-expanded="(!collapsed).toString()" aria-controls="sidebar" class="focus-ring flex min-h-11 w-full items-center gap-3 rounded-xl px-3 text-sm font-medium text-muted transition duration-fast hover:bg-tint hover:text-strong">
            <x-icon name="chevron-double-left" class="size-5 shrink-0 transition-transform duration-base rtl:-scale-x-100" x-bind:class="collapsed && 'rotate-180'" />
            <span class="min-w-0 truncate" x-bind:class="collapsed && 'sr-only'"><span x-show="!collapsed">{{ __('shell_app.collapse') }}</span><span x-show="collapsed" x-cloak>{{ __('shell_app.expand') }}</span></span>
        </button>
    </div>
</aside>
