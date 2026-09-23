import collapse from '@alpinejs/collapse';
import AOS from 'aos';
import 'aos/dist/aos.css';

// Livewire bundles its OWN Alpine instance (with the Navigate/Morph
// plugins it needs for wire:navigate already registered) via
// @livewireScripts and calls Alpine.start() itself right after firing
// 'livewire:init'. Importing/starting a separate Alpine instance here
// raced with that — Livewire's navigate plugin never attached correctly,
// causing "Alpine.navigate is not a function" on every redirect. Register
// our own plugins onto Livewire's instance in this hook instead of
// managing Alpine ourselves.
document.addEventListener('livewire:init', () => {
    window.Alpine.plugin(collapse);
});

// Dark mode: <x-theme-init-script> (in <head>, before this script loads)
// already applies/removes the .dark class on document.documentElement
// based on localStorage/prefers-color-scheme to avoid a flash of the
// wrong theme. <x-theme-toggle> reads/toggles that class directly via
// its own local x-data — no global store needed for either.

AOS.init({
    duration: 600,
    once: true,
});

document.addEventListener('livewire:navigated', () => AOS.refreshHard());
