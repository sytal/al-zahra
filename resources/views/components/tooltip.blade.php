@props(['text' => null, 'placement' => 'top'])

@php
$tipId = 'tip-' . substr(md5((string) $text . $placement . microtime()), 0, 8);
$place = $placement === 'bottom' ? 'top-full mt-2' : 'bottom-full mb-2';
@endphp

<span
    x-data="{ open: false, place() { const t = this.$refs.tip; t.style.translate = ''; const r = t.getBoundingClientRect(); const w = document.documentElement.clientWidth; const pad = 8; let dx = 0; if (r.left < pad) dx = pad - r.left; else if (r.right > w - pad) dx = w - pad - r.right; if (dx) t.style.translate = dx + 'px 0' } }"
    x-effect="if (open) $nextTick(() => place())"
    x-init="$nextTick(() => $el.firstElementChild?.setAttribute('aria-describedby', '{{ $tipId }}'))"
    x-on:mouseenter="open = true"
    x-on:mouseleave="open = false"
    x-on:focusin="open = true"
    x-on:focusout="open = false"
    x-on:keydown.escape="open = false"
    x-on:touchstart.passive="open = true"
    x-on:click.outside="open = false"
    {{ $attributes->merge(['class' => 'relative inline-flex']) }}
>
    {{ $slot }}
    <span
        id="{{ $tipId }}"
        x-ref="tip"
        role="tooltip"
        x-show="open"
        x-cloak
        x-transition:enter="transition duration-fast ease-enter"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition duration-fast ease-exit"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="pointer-events-none absolute start-1/2 z-dropdown w-max max-w-[min(16rem,calc(100vw-2rem))] -translate-x-1/2 rounded-lg bg-strong px-3 py-1.5 text-center text-xs font-medium leading-snug text-inverse shadow-float rtl:translate-x-1/2 {{ $place }}"
    >{{ $text ?? ($content ?? '') }}</span>
</span>
