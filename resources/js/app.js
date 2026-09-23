

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import AOS from 'aos';
import 'aos/dist/aos.css';

Alpine.plugin(collapse);

// Dark mode: <x-theme-init-script> (in <head>, before this script loads)
// already applies/removes the .dark class on document.documentElement
// based on localStorage/prefers-color-scheme to avoid a flash of the
// wrong theme. <x-theme-toggle> reads/toggles that class directly via
// its own local x-data — no global store needed for either.

window.Alpine = Alpine;

Alpine.start();

AOS.init({
    duration: 600,
    once: true,
});

document.addEventListener('livewire:navigated', () => AOS.refreshHard());
