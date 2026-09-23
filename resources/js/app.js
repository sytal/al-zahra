

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import AOS from 'aos';
import 'aos/dist/aos.css';

Alpine.plugin(collapse);

Alpine.store('theme', {
    dark: localStorage.getItem('theme') === 'dark'
        || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),

    toggle() {
        this.dark = !this.dark;
        localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        document.documentElement.classList.toggle('dark', this.dark);
    },
});

document.documentElement.classList.toggle('dark', Alpine.store('theme').dark);

window.Alpine = Alpine;

Alpine.start();

AOS.init({
    duration: 600,
    once: true,
});

document.addEventListener('livewire:navigated', () => AOS.refreshHard());
