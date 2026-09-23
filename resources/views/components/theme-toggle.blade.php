<button
    type="button"
    x-data="{ dark: document.documentElement.classList.contains('dark') }"
    x-on:click="
        dark = !dark;
        document.documentElement.classList.toggle('dark', dark);
        localStorage.setItem('theme', dark ? 'dark' : 'light');
    "
    class="rounded-lg p-2.5 text-ink/70 transition duration-200 ease-in-out hover:bg-surface hover:text-ink focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary"
>
    <span class="sr-only">{{ __('common.toggle_theme') }}</span>
    <x-icon name="sun" class="size-5" x-show="!dark" x-cloak />
    <x-icon name="moon" class="size-5" x-show="dark" x-cloak />
</button>
