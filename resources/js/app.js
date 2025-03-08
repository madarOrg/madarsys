import './bootstrap'; // تحميل الإعدادات الأساسية

// الاستماع للأحداث في Livewire
Livewire.on('alert', (data) => {
    Swal.fire({
        icon: data.type,
        title: data.message,
        showConfirmButton: false,
        // يمكن إضافة timer إذا كنت ترغب بإغلاق الإشعار تلقائيًا بعد مدة
        // timer: 3000
    });
});



document.addEventListener("DOMContentLoaded", function() {
    //  تفعيل زر تغيير الثيم (الوضع الداكن/المضيء)
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

    // القائمة المنسدلة للهاتف المحمول
    const mobileMenuButton = document.querySelector("[data-collapse-toggle='mobile-menu-2']");
    const mobileMenu = document.getElementById("mobile-menu-2");

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener("click", function() {
            mobileMenu.classList.toggle("hidden");
        });
    }

    //  القوائم المنسدلة الأخرى
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

 // إظهار/إخفاء الإشعارات عند الضغط على زر الإشعارات
 document.getElementById("notificationsLink").addEventListener("click", function(event) {
    event.preventDefault();  // منع الانتقال إلى الرابط
    const notificationsDropdown = document.getElementById("notificationsDropdown");
    
    // التبديل بين إظهار وإخفاء القائمة المنسدلة
    notificationsDropdown.classList.toggle("hidden");
  });
  
  // إخفاء القائمة عند الضغط في أي مكان آخر
  document.addEventListener("click", function(event) {
    const notificationsDropdown = document.getElementById("notificationsDropdown");
    const notificationsLink = document.getElementById("notificationsLink");
    
    // إذا تم الضغط خارج الرابط أو القائمة، قم بإخفائها
    if (!notificationsLink.contains(event.target) && !notificationsDropdown.contains(event.target)) {
      notificationsDropdown.classList.add("hidden");
    }
  });
  
  // توجيه القائمة المنسدلة عند النقر عليها في الهاتف المحمول
  document.querySelectorAll('li.relative > a').forEach(item => {
    item.addEventListener('click', function(event) {
      const submenu = this.nextElementSibling;
      if (submenu && submenu.classList.contains('hidden')) {
        submenu.classList.remove('hidden');
      } else if (submenu) {
        submenu.classList.add('hidden');
      }
    });
  });

