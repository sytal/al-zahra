<script>
    (function () {
        function applyTheme() {
            var stored = localStorage.getItem('theme');
            var dark = stored === 'dark' || (!stored && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', dark);
        }
        applyTheme();
        document.addEventListener('livewire:navigated', applyTheme);
    })();
</script>
