<button
    type="button"
    x-data
    x-on:click="$store.theme.toggle()"
    class="rounded-sm p-2 text-ink/70 transition duration-200 ease-in-out hover:bg-surface hover:text-ink"
>
    <span class="sr-only">{{ __('common.toggle_theme') }}</span>
    <x-icon name="sun" class="size-5" x-show="!$store.theme.dark" />
    <x-icon name="moon" class="size-5" x-show="$store.theme.dark" x-cloak />
</button>
