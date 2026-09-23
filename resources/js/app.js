

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import AOS from 'aos';
import 'aos/dist/aos.css';

Alpine.plugin(collapse);
window.Alpine = Alpine;

Alpine.start();

AOS.init({
    duration: 600,
    once: true,
});

document.addEventListener('livewire:navigated', () => AOS.refreshHard());
