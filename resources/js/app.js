import './bootstrap';
import { Dismiss } from 'flowbite';

// الثيم
const themeToggle = document.getElementById('theme-toggle');
if (themeToggle) {
    const icon = themeToggle.querySelector('i');
    const savedTheme = localStorage.getItem('theme') || 'light';

    function applyTheme(theme) {
        const isDark = theme === 'dark';
        document.documentElement.classList.toggle('dark', isDark);
        localStorage.setItem('theme', theme);
        icon.classList.toggle('fa-moon', !isDark);
        icon.classList.toggle('fa-sun', isDark);
    }

    applyTheme(savedTheme);

    themeToggle.addEventListener('click', () => {
        const currentTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
        applyTheme(currentTheme);
    });
}

// القوائم المنسدلة
document.querySelectorAll('.relative').forEach(item => {
    const dropdown = item.querySelector('ul');
    if (!dropdown) return;

    item.addEventListener('click', event => {
        event.stopPropagation();
        dropdown.classList.toggle('hidden');
    });
});

document.addEventListener('click', () => {
    document.querySelectorAll('.relative ul').forEach(dropdown => {
        dropdown.classList.add('hidden');
    });
});

// إخفاء العناصر باستخدام Flowbite
const $targetEl = document.getElementById('targetElement');
const $triggerEl = document.getElementById('triggerElement');

if ($targetEl && $triggerEl) {
    const options = {
        transition: 'transition-opacity',
        duration: 1000,
        timing: 'ease-out',
        onHide: (context, targetEl) => {
            console.log('تم إخفاء العنصر:', targetEl);
        },
    };

    new Dismiss($targetEl, $triggerEl, options, { id: 'targetElement', override: true });
}
