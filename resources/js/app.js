import './bootstrap';
import { Dismiss } from 'flowbite';

    window.addEventListener('alert', event => {
        Swal.fire({
            icon: event.detail.type,
            text: event.detail.message,
            timer: 3000,
            showConfirmButton: false
        });
    });


    // Swal.fire({
    //     title: "Custom width, padding, color, background.",
    //     width: 600,
    //     padding: "3em",
    //     color: "#716add",
    //     background: "#fff url(/images/trees.png)",
    //     backdrop: `
    //       rgba(0,0,123,0.4)
    //       url("/storage/logos/cat_flye.gif")
    //       left top
    //       no-repeat
    //     `
    //   });
    // });


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
