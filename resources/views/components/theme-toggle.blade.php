<button
    type="button"
    x-data="{ dark: document.documentElement.classList.contains('dark') }"
    x-on:click="
        dark = !dark;
        document.documentElement.classList.toggle('dark', dark);
        localStorage.setItem('theme', dark ? 'dark' : 'light');
    "
    x-bind:aria-pressed="dark.toString()"
    {{ $attributes->class(['tap-target relative shrink-0 rounded-full text-muted transition duration-200 hover:bg-tint hover:text-strong focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary active:scale-[0.96]']) }}
>
    <span class="sr-only">{{ __('common.toggle_theme') }}</span>
    <x-icon name="sun" class="size-5" x-show="!dark" x-cloak />
    <x-icon name="moon" class="size-5" x-show="dark" x-cloak />
</button>
