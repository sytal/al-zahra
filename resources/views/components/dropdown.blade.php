@props(['align' => 'end', 'width' => 'w-56'])

@php
$side = $align === 'end' ? 'end-0 ltr:origin-top-right rtl:origin-top-left' : 'start-0 ltr:origin-top-left rtl:origin-top-right';
@endphp

<div
    x-data="{
        open: false,
        toggle() { this.open = !this.open; if (this.open) this.$nextTick(() => { this.place(); this.focusItem(0) }) },
        place() { const m = this.$refs.menu; m.style.translate = ''; const host = this.$el.getBoundingClientRect(); const left = host.left + m.offsetLeft; const right = left + m.offsetWidth; const pad = 8; const w = document.documentElement.clientWidth; let dx = 0; if (left < pad) dx = pad - left; else if (right > w - pad) dx = w - pad - right; if (dx) m.style.translate = dx + 'px 0' },
        close(focusTrigger = false) { this.open = false; if (focusTrigger) this.trigger()?.focus() },
        trigger() { return this.$refs.trigger.querySelector('button, a, [tabindex]') },
        items() { return Array.from(this.$refs.menu.querySelectorAll('a[href], button:not([disabled])')) },
        focusItem(i) { const list = this.items(); if (list.length) list[(i + list.length) % list.length].focus() },
        move(step) { const list = this.items(); const at = list.indexOf(document.activeElement); this.focusItem(at < 0 ? (step > 0 ? 0 : -1) : at + step) },
    }"
    x-init="const t = trigger(); if (t) { t.setAttribute('aria-haspopup', 'menu'); t.setAttribute('aria-expanded', 'false'); $watch('open', v => t.setAttribute('aria-expanded', String(v))) }"
    x-on:click.outside="close()"
    x-on:keydown.escape.prevent="open && close(true)"
    x-on:focusin.window="open && !$el.contains($event.target) && close()"
    class="relative"
>
    <div x-ref="trigger" x-on:click="toggle()" x-on:keydown.arrow-down.prevent="!open ? toggle() : move(1)">
        {{ $trigger }}
    </div>

    <div
        x-ref="menu"
        x-show="open"
        x-cloak
        role="menu"
        x-transition:enter="transition duration-fast ease-enter"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition duration-fast ease-exit"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-on:keydown.arrow-down.prevent="move(1)"
        x-on:keydown.arrow-up.prevent="move(-1)"
        x-on:keydown.home.prevent="focusItem(0)"
        x-on:keydown.end.prevent="focusItem(-1)"
        x-on:keydown.tab="close()"
        x-on:click="close()"
        class="absolute {{ $side }} z-dropdown mt-2 max-h-[70dvh] max-w-[calc(100vw-2rem)] overflow-y-auto overscroll-contain rounded-2xl border border-subtle bg-surface-raised p-1.5 shadow-float {{ $width }} [&_a]:flex [&_a]:min-h-11 [&_a]:items-center [&_a]:gap-2 [&_a]:rounded-xl [&_a]:px-3 [&_a]:py-2 [&_a]:text-sm [&_a]:font-medium [&_a]:text-body [&_a]:outline-none [&_a]:transition-colors [&_a]:duration-fast [&_a:focus-visible]:bg-tint [&_a:focus-visible]:ring-2 [&_a:focus-visible]:ring-brand [&_button]:flex [&_button]:min-h-11 [&_button]:w-full [&_button]:items-center [&_button]:gap-2 [&_button]:rounded-xl [&_button]:px-3 [&_button]:text-start [&_button]:text-sm [&_button]:font-medium [&_button]:text-body [&_button]:outline-none [&_button:focus-visible]:bg-tint [&_button:focus-visible]:ring-2 [&_button:focus-visible]:ring-brand [@media(hover:hover)]:[&_a:hover]:bg-tint [@media(hover:hover)]:[&_button:hover]:bg-tint"
    >
        {{ $content }}
    </div>
</div>
