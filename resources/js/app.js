import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// ── Dark Mode Toggle ─────────────────────────────────────────
// Reads / writes the 'theme' key in localStorage.
// Applies/removes the 'dark' class on <html> immediately on load
// so there is no flash of wrong theme.
(function () {
    const html = document.documentElement;
    const saved = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (saved === 'dark' || (!saved && prefersDark)) {
        html.classList.add('dark');
    } else {
        html.classList.remove('dark');
    }

    window.toggleDarkMode = function () {
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    };
})();

Alpine.start();
