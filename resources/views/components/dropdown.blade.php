@props(['align' => 'end'])

<div x-data="{ open: false }" x-on:click.outside="open = false" class="relative">
    <div x-on:click="open = !open">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-transition
        x-cloak
        class="absolute {{ $align === 'end' ? 'end-0' : 'start-0' }} z-40 mt-2 min-w-40 rounded-lg border border-ink/10 bg-white dark:bg-surface py-1 shadow-lg"
        x-on:click="open = false"
    >
        {{ $content }}
    </div>
</div>
