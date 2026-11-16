<script>
    (() => {
        const key = 'invest.theme';
        const system = window.matchMedia('(prefers-color-scheme: dark)');
        const root = document.documentElement;
        let preference = 'system';

        function apply(value) {
            preference = ['light', 'dark'].includes(value) ? value : 'system';
            const theme = preference === 'system' ? (system.matches ? 'dark' : 'light') : preference;
            root.dataset.themePreference = preference;
            const control = document.getElementById('theme-preference');
            if (control) control.value = preference;
            if (root.dataset.bsTheme !== theme) {
                root.dataset.bsTheme = theme;
                window.dispatchEvent(new Event('invest:themechange'));
            }
        }

        function restore() {
            try {
                apply(localStorage.getItem(key));
            } catch {
                apply(preference);
            }
        }

        restore();
        document.addEventListener('DOMContentLoaded', () => {
            const control = document.getElementById('theme-preference');
            control.value = preference;
            control.addEventListener('change', () => {
                apply(control.value);
                try { localStorage.setItem(key, preference); } catch { /* Storage can be unavailable. */ }
            });
        });
        system.addEventListener('change', () => apply(preference));
        window.addEventListener('storage', (event) => {
            if (event.key === key || event.key === null) restore();
        });
        window.addEventListener('pageshow', restore);
    })();
</script>
