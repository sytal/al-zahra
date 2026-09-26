import collapse from '@alpinejs/collapse';
import AOS from 'aos';
import 'aos/dist/aos.css';

// Livewire bundles its OWN Alpine instance (with the Navigate/Morph
// plugins it needs for wire:navigate already registered) via
// @livewireScripts and calls Alpine.start() itself right after firing
// 'livewire:init'. Importing/starting a separate Alpine instance here
// raced with that, causing "Alpine.navigate is not a function" on every
// redirect. Register plugins and components onto Livewire's instance
// instead of managing Alpine ourselves.
document.addEventListener('livewire:init', () => {
    window.Alpine.plugin(collapse);
});

const reducedMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const canHover = () => window.matchMedia('(hover: hover) and (pointer: fine)').matches;
const isRtl = () => document.documentElement.dir === 'rtl';

window.toast = (message, type = 'info', duration = 4000) =>
    window.dispatchEvent(new CustomEvent('toast', { detail: { message, type, duration } }));

document.addEventListener('alpine:init', () => {
    const Alpine = window.Alpine;

    // <span x-data="counter({ to: 1200, locale: 'en' })" x-text="display">1,200</span>
    Alpine.data('counter', ({ to = 0, duration = 1600, locale, decimals = 0, prefix = '', suffix = '' } = {}) => ({
        display: '',
        format(n) {
            const num = new Intl.NumberFormat(locale || document.documentElement.lang || undefined, {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals,
            }).format(n);
            return `${prefix}${num}${suffix}`;
        },
        init() {
            const end = () => (this.display = this.format(to));
            if (reducedMotion() || !('IntersectionObserver' in window)) return end();
            this.display = this.format(0);
            const io = new IntersectionObserver((entries) => {
                if (!entries.some((e) => e.isIntersecting)) return;
                io.disconnect();
                const start = performance.now();
                const tick = (now) => {
                    const p = Math.min((now - start) / duration, 1);
                    this.display = this.format(to * (1 - Math.pow(1 - p, 3)));
                    if (p < 1) requestAnimationFrame(tick);
                };
                requestAnimationFrame(tick);
            }, { threshold: 0.4 });
            io.observe(this.$el);
            this.$cleanup?.(() => io.disconnect());
        },
    }));

    // <div class="spotlight" x-data="spotlight">
    Alpine.data('spotlight', () => ({
        init() {
            if (!canHover() || reducedMotion()) return;
            let frame = 0;
            this.$el.addEventListener('pointermove', (e) => {
                if (e.pointerType === 'touch') return;
                cancelAnimationFrame(frame);
                frame = requestAnimationFrame(() => {
                    const r = this.$el.getBoundingClientRect();
                    this.$el.style.setProperty('--mx', `${e.clientX - r.left}px`);
                    this.$el.style.setProperty('--my', `${e.clientY - r.top}px`);
                });
            }, { passive: true });
        },
    }));

    // <div class="tilt" x-data="tilt({ max: 6 })">
    Alpine.data('tilt', ({ max = 6 } = {}) => ({
        init() {
            if (!canHover() || reducedMotion()) return;
            const el = this.$el;
            el.addEventListener('pointermove', (e) => {
                if (e.pointerType === 'touch') return;
                const r = el.getBoundingClientRect();
                const x = (e.clientX - r.left) / r.width - 0.5;
                const y = (e.clientY - r.top) / r.height - 0.5;
                el.style.setProperty('--ry', `${x * max * 2}deg`);
                el.style.setProperty('--rx', `${-y * max * 2}deg`);
            }, { passive: true });
            el.addEventListener('pointerleave', () => {
                el.style.setProperty('--rx', '0deg');
                el.style.setProperty('--ry', '0deg');
            });
        },
    }));

    // <div x-data="reveal({ delay: 80 })" class="reveal">
    Alpine.data('reveal', ({ delay = 0 } = {}) => ({
        init() {
            const el = this.$el;
            if (reducedMotion() || !('IntersectionObserver' in window)) return;
            el.style.setProperty('--reveal-delay', `${delay}ms`);
            el.classList.add('reveal-ready');
            const io = new IntersectionObserver((entries) => {
                if (!entries.some((e) => e.isIntersecting)) return;
                el.classList.add('is-visible');
                io.disconnect();
            }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
            io.observe(el);
        },
    }));

    // <div x-data="tabs({ initial: 'a' })">
    //   <div role="tablist"><button x-bind="tab('a')">A</button></div>
    //   <div x-bind="panel('a')">...</div></div>
    Alpine.data('tabs', ({ initial = null } = {}) => ({
        active: initial,
        ids: [],
        init() {
            this.ids = [...this.$el.querySelectorAll('[role=tab]')].map((b) => b.dataset.tab);
            if (!this.active) this.active = this.ids[0];
        },
        select(id) { this.active = id; },
        tab(id) {
            const self = this;
            return {
                role: 'tab',
                'data-tab': id,
                'x-bind:aria-selected': () => String(self.active === id),
                'x-bind:tabindex': () => (self.active === id ? 0 : -1),
                'x-on:click': () => self.select(id),
                'x-on:keydown'(e) {
                    const i = self.ids.indexOf(id);
                    const fwd = isRtl() ? 'ArrowLeft' : 'ArrowRight';
                    const back = isRtl() ? 'ArrowRight' : 'ArrowLeft';
                    let n = null;
                    if (e.key === fwd) n = (i + 1) % self.ids.length;
                    else if (e.key === back) n = (i - 1 + self.ids.length) % self.ids.length;
                    else if (e.key === 'Home') n = 0;
                    else if (e.key === 'End') n = self.ids.length - 1;
                    if (n === null) return;
                    e.preventDefault();
                    self.select(self.ids[n]);
                    self.$el.parentElement.querySelector(`[data-tab="${self.ids[n]}"]`)?.focus();
                },
            };
        },
        panel(id) {
            const self = this;
            return { role: 'tabpanel', 'x-show': () => self.active === id, 'x-cloak': '' };
        },
    }));

    // <div x-data="carousel({ autoplay: 5000 })"> <div x-ref="track" class="snap-track">items</div>
    //   <button @click="prev" :disabled="!canPrev"> <button @click="next" :disabled="!canNext"></div>
    Alpine.data('carousel', ({ autoplay = 0 } = {}) => ({
        canPrev: false,
        canNext: true,
        timer: null,
        paused: false,
        init() {
            const t = this.$refs.track;
            t.addEventListener('scroll', () => this.update(), { passive: true });
            window.addEventListener('resize', () => this.update(), { passive: true });
            this.update();
            if (autoplay > 0 && !reducedMotion()) {
                this.$el.addEventListener('pointerenter', () => (this.paused = true));
                this.$el.addEventListener('pointerleave', () => (this.paused = false));
                this.$el.addEventListener('focusin', () => (this.paused = true));
                this.$el.addEventListener('focusout', () => (this.paused = false));
                this.timer = setInterval(() => {
                    if (this.paused || document.hidden) return;
                    this.canNext ? this.next() : t.scrollTo({ left: 0, behavior: 'smooth' });
                }, autoplay);
            }
        },
        destroy() { clearInterval(this.timer); },
        update() {
            const t = this.$refs.track;
            const pos = Math.abs(t.scrollLeft);
            this.canPrev = pos > 4;
            this.canNext = pos + t.clientWidth < t.scrollWidth - 4;
        },
        by(sign) {
            const t = this.$refs.track;
            const d = (isRtl() ? -1 : 1) * sign * t.clientWidth * 0.9;
            t.scrollBy({ left: d, behavior: reducedMotion() ? 'auto' : 'smooth' });
        },
        next() { this.by(1); },
        prev() { this.by(-1); },
    }));

    // <header x-data="stickyHeader" :class="{ 'is-scrolled': scrolled }">
    Alpine.data('stickyHeader', ({ offset = 8 } = {}) => ({
        scrolled: false,
        init() {
            let frame = 0;
            const check = () => { this.scrolled = window.scrollY > offset; };
            window.addEventListener('scroll', () => {
                cancelAnimationFrame(frame);
                frame = requestAnimationFrame(check);
            }, { passive: true });
            check();
        },
    }));

    // <div x-data="backToTop" x-show="visible"><button @click="top">
    Alpine.data('backToTop', ({ threshold = 400 } = {}) => ({
        visible: false,
        init() {
            const check = () => { this.visible = window.scrollY > threshold; };
            window.addEventListener('scroll', check, { passive: true });
            check();
        },
        top() { window.scrollTo({ top: 0, behavior: reducedMotion() ? 'auto' : 'smooth' }); },
    }));

    // <button x-data="copyToClipboard({ text: 'a@b.c', message: 'Copied' })" @click="copy">
    Alpine.data('copyToClipboard', ({ text = '', timeout = 2000, message = '' } = {}) => ({
        copied: false,
        async copy() {
            try {
                if (navigator.clipboard?.writeText) {
                    await navigator.clipboard.writeText(text);
                } else {
                    const ta = Object.assign(document.createElement('textarea'), { value: text });
                    ta.style.position = 'fixed';
                    ta.style.opacity = '0';
                    document.body.appendChild(ta);
                    ta.select();
                    document.execCommand('copy');
                    ta.remove();
                }
                this.copied = true;
                if (message) window.toast(message, 'success');
                setTimeout(() => (this.copied = false), timeout);
            } catch (e) {
                this.copied = false;
            }
        },
    }));

    // <a class="magnetic" x-data="magnetic({ strength: 0.3 })">
    Alpine.data('magnetic', ({ strength = 0.3, radius = 80 } = {}) => ({
        init() {
            if (!canHover() || reducedMotion()) return;
            const el = this.$el;
            const move = (e) => {
                const r = el.getBoundingClientRect();
                const dx = e.clientX - (r.left + r.width / 2);
                const dy = e.clientY - (r.top + r.height / 2);
                const near = Math.abs(dx) < r.width / 2 + radius && Math.abs(dy) < r.height / 2 + radius;
                el.style.setProperty('--tx', near ? `${dx * strength}px` : '0px');
                el.style.setProperty('--ty', near ? `${dy * strength}px` : '0px');
            };
            const reset = () => { el.style.setProperty('--tx', '0px'); el.style.setProperty('--ty', '0px'); };
            const onEnter = () => window.addEventListener('pointermove', move, { passive: true });
            el.addEventListener('pointerenter', onEnter, { passive: true });
            el.addEventListener('pointerleave', () => { window.removeEventListener('pointermove', move); reset(); });
        },
    }));

    // <div class="star-burst" x-data="starBurst" :class="{ 'is-bursting': bursting }" @star-burst.window="fire">
    Alpine.data('starBurst', () => ({
        bursting: false,
        fire() {
            if (reducedMotion()) return;
            this.bursting = false;
            requestAnimationFrame(() => {
                this.bursting = true;
                setTimeout(() => (this.bursting = false), 1000);
            });
        },
    }));

    // <div x-data="confetti" @confetti.window="fire()"> or call window.confetti(); tiny, no dependency
    Alpine.data('confetti', ({ count = 28 } = {}) => ({
        fire(origin) {
            if (reducedMotion()) return;
            const cs = getComputedStyle(document.documentElement);
            const colors = ['--color-brand-primary', '--color-brand-secondary', '--color-brand-secondary-300', '--color-brand-primary-300']
                .map((v) => cs.getPropertyValue(v).trim());
            const ox = origin?.x ?? window.innerWidth / 2;
            const oy = origin?.y ?? window.innerHeight / 3;
            for (let i = 0; i < count; i++) {
                const p = document.createElement('span');
                p.className = 'confetti-piece';
                p.style.left = `${ox}px`;
                p.style.top = `${oy}px`;
                p.style.background = colors[i % colors.length];
                document.body.appendChild(p);
                const a = Math.random() * Math.PI * 2;
                const d = 80 + Math.random() * 160;
                p.animate([
                    { transform: 'translate(0,0) rotate(0deg)', opacity: 1 },
                    { transform: `translate(${Math.cos(a) * d}px, ${Math.sin(a) * d + 120}px) rotate(${Math.random() * 720}deg)`, opacity: 0 },
                ], { duration: 900 + Math.random() * 500, easing: 'cubic-bezier(0.22,1,0.36,1)', fill: 'forwards' })
                    .onfinish = () => p.remove();
            }
        },
    }));

    // <div x-data="toast" @toast.window="add($event.detail)"> ... x-for="t in items"
    // Fire from anywhere: window.toast('Saved', 'success') or Livewire $this->dispatch('toast', ...)
    Alpine.data('toast', () => ({
        items: [],
        n: 0,
        add(detail) {
            const d = Array.isArray(detail) ? detail[0] : detail;
            const item = { id: ++this.n, message: '', type: 'info', duration: 4000, ...(d || {}) };
            this.items.push(item);
            if (item.duration > 0) setTimeout(() => this.dismiss(item.id), item.duration);
        },
        dismiss(id) { this.items = this.items.filter((i) => i.id !== id); },
    }));
});

// Dark mode: <x-theme-init-script> (in <head>, before this script loads)
// already applies/removes the .dark class on document.documentElement
// based on localStorage/prefers-color-scheme to avoid a flash of the
// wrong theme. <x-theme-toggle> reads/toggles that class directly via
// its own local x-data, no global store needed for either.

AOS.init({
    duration: 600,
    once: true,
    disable: () => window.matchMedia('(prefers-reduced-motion: reduce)').matches,
});

document.addEventListener('livewire:navigated', () => AOS.refreshHard());

// Livewire morphs strip the classes AOS adds, leaving elements at opacity 0.
document.addEventListener('livewire:init', () => {
    window.Livewire.hook('morphed', () => AOS.refreshHard());
});
