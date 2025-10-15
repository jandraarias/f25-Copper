// app.js

import './bootstrap';
import Alpine from 'alpinejs';
import countrySelect from './countrySelect';

// --- Theme persistence & flicker fix ---
(function () {
    const storedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const shouldUseDark = storedTheme === 'dark' || (!storedTheme && systemPrefersDark);

    // Set immediately before Alpine starts
    if (shouldUseDark) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    // Optional: smooth transition only during toggle, not on load
    window.enableThemeTransition = function () {
        document.documentElement.classList.add('theme-transition');
        window.setTimeout(() => {
            document.documentElement.classList.remove('theme-transition');
        }, 400);
    };

    // Toggle theme handler for Alpine or global JS
    window.toggleTheme = function () {
        const html = document.documentElement;
        const isDark = html.classList.contains('dark');

        window.enableThemeTransition();
        if (isDark) {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    };
})();

// --- Alpine setup ---
Alpine.data('countrySelect', countrySelect);

// Optionally, you can make toggleTheme accessible via Alpine
Alpine.store('theme', {
    dark: document.documentElement.classList.contains('dark'),
    toggle() {
        window.toggleTheme();
        this.dark = !this.dark;
    },
});

window.Alpine = Alpine;
Alpine.start();
