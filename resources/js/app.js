// resources/js/app.js

import './bootstrap';
import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect'; // Added for x-intersect animations
import countrySelect from './countrySelect';

// --- Theme persistence & flicker fix ---
(function () {
    const storedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const shouldUseDark = storedTheme === 'dark' || (!storedTheme && systemPrefersDark);

    // Apply before Alpine initializes
    if (shouldUseDark) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    // Optional: smooth transition only during toggle, not on load
    window.enableThemeTransition = function () {
        document.documentElement.classList.add('theme-transition');
        setTimeout(() => {
            document.documentElement.classList.remove('theme-transition');
        }, 400);
    };

    // Global theme toggle (usable by Alpine or plain JS)
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
Alpine.plugin(intersect); // Enables x-intersect animations everywhere
Alpine.data('countrySelect', countrySelect);

// Optional global theme store (Alpine-aware)
Alpine.store('theme', {
    dark: document.documentElement.classList.contains('dark'),
    toggle() {
        window.toggleTheme();
        this.dark = !this.dark;
    },
});

// Expose Alpine globally
window.Alpine = Alpine;
Alpine.start();
