@props(['items' => []])

<div class="divide-y divide-ink/10 rounded-xl border border-ink/10">
    @foreach ($items as $i => $item)
        <div x-data="{ open: {{ $i === 0 ? 'true' : 'false' }} }">
            <button
                type="button"
                x-on:click="open = !open"
                class="flex w-full items-center justify-between px-4 py-3 text-start text-sm font-medium text-ink"
            >
                {{ $item['title'] }}
                <x-icon name="chevron-down" class="size-4 transition duration-200" x-bind:class="open && 'rotate-180'" />
            </button>
            <div x-show="open" x-collapse class="px-4 pb-4 text-sm text-ink/70">
                {{ $item['content'] }}
            </div>
        </div>
    @endforeach
</div>
