{{-- Props: target = CSS selector of the content to scan for h2/h3 (default ".prose-content"), title. Builds itself with Alpine, hides when fewer than 2 headings, scroll-spy highlights the current section. Collapsible on mobile, always open from lg. --}}
@props(['target' => '.prose-content', 'title' => null])

@php $tid = 'toc-' . \Illuminate\Support\Str::random(5); @endphp

<nav {{ $attributes->merge(['class' => 'min-w-0']) }} aria-label="{{ $title ?? __('kit_sections.on_this_page') }}" x-cloak
    x-data="{
        items: [],
        active: '',
        open: false,
        init() {
            this.$nextTick(() => {
                const root = document.querySelector(@js($target));
                if (!root) return;
                const used = new Set();
                this.items = [...root.querySelectorAll('h2, h3')].map((h, i) => {
                    let id = h.id;
                    if (!id) {
                        id = h.textContent.trim().toLowerCase().replace(/[^\p{L}\p{N}]+/gu, '-').replace(/^-+|-+$/g, '') || 'section-' + (i + 1);
                        while (used.has(id)) id += '-' + (i + 1);
                        h.id = id;
                    }
                    used.add(id);
                    h.style.scrollMarginTop = '6rem';
                    return { id, text: h.textContent.trim(), level: h.tagName === 'H3' ? 3 : 2, el: h };
                });
                if (!('IntersectionObserver' in window) || this.items.length < 2) return;
                const io = new IntersectionObserver((entries) => {
                    entries.forEach((e) => { if (e.isIntersecting) this.active = e.target.id; });
                }, { rootMargin: '-15% 0px -70% 0px' });
                this.items.forEach((i) => io.observe(i.el));
                this.$cleanup?.(() => io.disconnect());
            });
        },
        go(item) {
            item.el.scrollIntoView({ behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'start' });
            history.replaceState(null, '', '#' + item.id);
            this.active = item.id;
            this.open = false;
        },
    }"
    x-show="items.length > 1">
    <button type="button" class="focus-ring flex min-h-11 w-full items-center justify-between gap-3 rounded-card border bg-surface-raised px-4 text-start text-sm font-semibold text-strong lg:hidden"
        x-on:click="open = !open" x-bind:aria-expanded="open.toString()" aria-controls="{{ $tid }}">
        <span class="flex min-w-0 items-center gap-2"><span class="star-mark text-xs" aria-hidden="true"></span><span class="truncate">{{ $title ?? __('kit_sections.on_this_page') }}</span></span>
        <x-icon name="chevron-down" class="size-4 shrink-0 transition-transform duration-base" x-bind:class="open && 'rotate-180'" aria-hidden="true" />
    </button>
    <p class="mb-3 hidden items-center gap-2 text-sm font-semibold text-strong lg:flex"><span class="star-mark text-xs" aria-hidden="true"></span>{{ $title ?? __('kit_sections.on_this_page') }}</p>
    <div id="{{ $tid }}" class="max-lg:mt-2 max-lg:overflow-hidden" x-bind:class="!open && 'max-lg:hidden'">
        <ol class="max-h-[60vh] space-y-0.5 overflow-y-auto overscroll-contain border-s border-subtle text-sm max-lg:rounded-card max-lg:border max-lg:bg-surface-raised max-lg:p-2">
            <template x-for="item in items" :key="item.id">
                <li>
                    <a x-bind:href="'#' + item.id" x-on:click.prevent="go(item)"
                        class="focus-ring -ms-px block min-h-9 border-s-2 py-1.5 pe-2 leading-snug transition-colors duration-fast max-lg:border-transparent"
                        x-bind:class="[item.level === 3 ? 'ps-6' : 'ps-4', active === item.id ? 'border-brand-secondary font-semibold text-strong' : 'border-transparent text-body [@media(hover:hover)]:hover:text-brand-primary']"
                        x-text="item.text"></a>
                </li>
            </template>
        </ol>
    </div>
</nav>
