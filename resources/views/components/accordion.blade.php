@props(['items' => [], 'multiple' => true, 'firstOpen' => true, 'id' => null])

@php
$uid = $id ?? 'acc-' . substr(md5(json_encode(collect($items)->map(fn ($i) => (string) ($i['title'] ?? ''))->all())), 0, 8);
$initial = [];
foreach (array_values($items) as $i => $item) {
    if (($item['open'] ?? ($firstOpen && $i === 0)) === true) {
        $initial[] = $i;
    }
}
@endphp

<div
    x-data="{
        open: @js($multiple ? $initial : ($initial[0] ?? null)),
        multiple: @js((bool) $multiple),
        isOpen(i) { return this.multiple ? this.open.includes(i) : this.open === i },
        toggle(i) {
            if (this.multiple) this.open = this.open.includes(i) ? this.open.filter(x => x !== i) : [...this.open, i];
            else this.open = this.open === i ? null : i;
        },
        focusHeader(i) {
            const heads = Array.from(this.$el.querySelectorAll('[data-accordion-head]'));
            heads[(i + heads.length) % heads.length]?.focus();
        },
    }"
    {{ $attributes->merge(['class' => 'space-y-3']) }}
>
    @foreach (array_values($items) as $i => $item)
        <div
            class="overflow-hidden rounded-2xl border bg-surface-raised transition-[border-color,box-shadow] duration-base ease-enter"
            x-bind:class="isOpen({{ $i }}) ? 'border-brand/40 shadow-soft' : 'border-subtle'"
        >
            <h3>
                <button
                    type="button"
                    id="{{ $uid }}-head-{{ $i }}"
                    data-accordion-head
                    aria-controls="{{ $uid }}-panel-{{ $i }}"
                    x-bind:aria-expanded="isOpen({{ $i }}).toString()"
                    x-on:click="toggle({{ $i }})"
                    x-on:keydown.arrow-down.prevent="focusHeader({{ $i }} + 1)"
                    x-on:keydown.arrow-up.prevent="focusHeader({{ $i }} - 1)"
                    x-on:keydown.home.prevent="focusHeader(0)"
                    x-on:keydown.end.prevent="focusHeader(-1)"
                    class="flex min-h-14 w-full items-center gap-3 px-4 py-3 text-start outline-none transition-colors duration-fast focus-visible:bg-tint focus-visible:ring-4 focus-visible:ring-inset focus-visible:ring-brand/30 sm:px-5 [@media(hover:hover)]:hover:bg-tint"
                >
                    <span aria-hidden="true" class="star-mark shrink-0 text-sm transition-transform duration-base ease-spring" x-bind:class="isOpen({{ $i }}) && 'rotate-45 scale-125'"></span>
                    <span class="min-w-0 flex-1 break-words text-base font-semibold leading-snug text-strong">{{ $item['title'] }}</span>
                    <x-icon name="chevron-down" size="size-5" class="shrink-0 text-muted transition-transform duration-base ease-enter" x-bind:class="isOpen({{ $i }}) && 'rotate-180'" aria-hidden="true" />
                </button>
            </h3>
            <div
                id="{{ $uid }}-panel-{{ $i }}"
                role="region"
                aria-labelledby="{{ $uid }}-head-{{ $i }}"
                x-show="isOpen({{ $i }})"
                x-collapse
                x-cloak
            >
                <div class="break-words px-4 pb-5 ps-12 text-base leading-relaxed text-body sm:px-5 sm:pb-6 sm:ps-14">
                    {{ $item['content'] ?? '' }}
                </div>
            </div>
        </div>
    @endforeach
</div>
