document.addEventListener("DOMContentLoaded", function() {
    // ✅ تفعيل زر تغيير الثيم (الوضع الداكن/المضيء)
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

    // ✅ القائمة المنسدلة للهاتف المحمول
    const mobileMenuButton = document.querySelector("[data-collapse-toggle='mobile-menu-2']");
    const mobileMenu = document.getElementById("mobile-menu-2");

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener("click", function() {
            mobileMenu.classList.toggle("hidden");
        });
    }

    // ✅ القوائم المنسدلة الأخرى
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
});
