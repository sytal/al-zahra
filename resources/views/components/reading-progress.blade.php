{{-- Props: target = CSS selector of the content whose reading progress is tracked (default: the first <article>). Fixed hairline bar at the top, RTL aware, decorative. --}}
@props(['target' => 'article'])

<div {{ $attributes->merge(['class' => 'pointer-events-none fixed inset-x-0 top-0 z-dropdown h-1 bg-transparent']) }} aria-hidden="true"
    x-data="{
        p: 0,
        frame: 0,
        measure() {
            const el = document.querySelector(@js($target));
            if (!el) { this.p = 0; return; }
            const r = el.getBoundingClientRect();
            const total = r.height - window.innerHeight * 0.6;
            this.p = total <= 0 ? (r.bottom <= window.innerHeight ? 1 : 0) : Math.min(1, Math.max(0, -r.top / total));
        },
        schedule() { cancelAnimationFrame(this.frame); this.frame = requestAnimationFrame(() => this.measure()); },
    }"
    x-init="measure()"
    x-on:scroll.window.passive="schedule()"
    x-on:resize.window.passive="schedule()">
    <div class="h-full w-full origin-left bg-gold-gradient rtl:origin-right" x-bind:style="`transform: scaleX(${p})`" style="transform: scaleX(0)"></div>
</div>
