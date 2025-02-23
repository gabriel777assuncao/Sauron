import './bootstrap';
import Alpine from 'alpinejs';
import 'flowbite';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', function () {
    var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
    var themeToggleBtn = document.getElementById('theme-toggle');

    if (!themeToggleBtn) return;

    if (document.documentElement.classList.contains('dark')) {
        themeToggleDarkIcon.classList.add('hidden');
        themeToggleLightIcon.classList.remove('hidden');
    } else {
        themeToggleDarkIcon.classList.remove('hidden');
        themeToggleLightIcon.classList.add('hidden');
    }

    themeToggleBtn.addEventListener('click', function () {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
            themeToggleDarkIcon.classList.remove('hidden');
            themeToggleLightIcon.classList.add('hidden');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
            themeToggleDarkIcon.classList.add('hidden');
            themeToggleLightIcon.classList.remove('hidden');
        }
    });
});
