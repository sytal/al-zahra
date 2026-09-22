

import Alpine from 'alpinejs';
import AOS from 'aos';
import 'aos/dist/aos.css';

window.Alpine = Alpine;

Alpine.start();

AOS.init({
    duration: 600,
    once: true,
});

document.addEventListener('livewire:navigated', () => AOS.refreshHard());
