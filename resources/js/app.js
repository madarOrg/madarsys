import './bootstrap';
// dark mode
const themeToggle = document.getElementById('theme-toggle');
const icon = themeToggle.querySelector('i');

function applyTheme(theme) {
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
    }
}

const savedTheme = localStorage.getItem('theme') || 'light';
applyTheme(savedTheme);

themeToggle.addEventListener('click', () => {
    const currentTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
    applyTheme(currentTheme);
});

// // Sidebar Toggle Logic
// const sidebarToggle = document.getElementById('sidebar-toggle');
// const logoSidebar = document.getElementById('logo-sidebar');
// sidebarToggle.addEventListener('click', () => {
//     logoSidebar.classList.toggle('translate-x-0');
//     logoSidebar.classList.toggle('translate-x-full');
// });



//         const sidebar = document.getElementById('sidebar');
//         const sidebarText = document.querySelectorAll('.sidebar-text');

//         // التحكم في إظهار وإخفاء القائمة الجانبية في العرض الصغير
//         sidebarToggle.addEventListener('click', () => {
//             if (window.innerWidth < 1024) { // العرض الصغير
//                 sidebar.style.width = "16rem"; // تصفير العرض عند تحميل الصفحة على الشاشات الصغيرة
//                 sidebar.classList.toggle('-translate-x-full'); // تبديل حالة الإخفاء/الإظهار
//                 sidebarText.forEach(el => el.style.display = 'inline'); // إظهار النصوص
//             }
//         });

//         // مراقبة التغيرات في حجم الشاشة
//         window.addEventListener('resize', () => {
//             if (window.innerWidth >= 1024) { // العرض الكبير
//                 sidebar.classList.remove('-translate-x-full'); // إظهار القائمة الجانبية
//                  sidebar.style.width = "16rem"; // تصفير العرض عند تحميل الصفحة على الشاشات الصغيرة
//                 sidebarText.forEach(el => el.style.display = 'inline'); // إظهار النصوص
//             } else {
//                 // sidebar.classList.add('-translate-x-full'); // إخفاء القائمة الجانبية
//                 sidebar.style.width = "50px"; // تصفير العرض عند تحميل الصفحة على الشاشات الصغيرة
//                 sidebarText.forEach(el => el.style.display = 'none'); // إخفاء النصوص
//             }
//         });

//         // إخفاء النصوص عند العرض الصغير
//         window.addEventListener('load', () => {
//             if (window.innerWidth < 1024) {
//                 sidebar.style.width = "50px"; // تصفير العرض عند تحميل الصفحة على الشاشات الصغيرة
//                 sidebarText.forEach(el => el.style.display = 'none'); // إخفاء النصوص

//             }
//             else{
//                 if (window.innerWidth >= 1024) { // العرض الكبير
//                 sidebar.classList.remove('-translate-x-full'); // إظهار القائمة الجانبية
//                  sidebar.style.width = "16rem"; // تصفير العرض عند تحميل الصفحة على الشاشات الصغيرة
//                 sidebarText.forEach(el => el.style.display = 'inline'); // إظهار النصوص
//             }
//         }});
document.querySelectorAll('.relative').forEach(item => {
    let dropdown = item.querySelector('ul');
    
    // Toggle dropdown visibility on click
    item.addEventListener('click', function(event) {
      event.stopPropagation();  // Prevent event from bubbling up to document
      if (dropdown) {
        dropdown.classList.toggle('hidden');
      }
    });

    // // Optionally, hide the dropdown when mouse leaves the parent container
    // item.addEventListener('mouseleave', function() {
    //   if (dropdown) {
    //     dropdown.classList.add('hidden');
    //   }
    // });
});

// Close dropdown if click happens outside
document.addEventListener('click', function(event) {
  document.querySelectorAll('.relative').forEach(item => {
    let dropdown = item.querySelector('ul');
    if (dropdown && !item.contains(event.target)) {
      dropdown.classList.add('hidden');
    }
  });
});
