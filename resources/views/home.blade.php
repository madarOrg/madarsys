<x-home class="dark:bg-gray-900 text-gray-800 dark:text-white">
  <!-- إضافة الشعار المتحرك -->

  <style>
    .hover-card:hover {
      transform: translateY(-10px);
      transition: transform 0.3s ease;
    }

    .btn-hover:hover {
      transform: scale(1.05);
      transition: transform 0.3s ease;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .shadow-custom {
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .bg-hero {
      background: url('/images/dashboard.jpg') no-repeat center center/cover;
      height: 60vh;
      position: relative;
    }

    .bg-hero-overlay {
      background: rgba(0, 0, 0, 0.6);
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
    }

    /* تأثير الحركة للشعار عند تحميل الصفحة */
    .logo-animation {
      position: absolute;
      top: 10px;   /* ازاحة الشعار قليلا الى الداخل */
      left: 10px;  /* ازاحة الشعار قليلا الى الداخل */
      width: 50px; /* تقليص حجم الشعار */
    }
/* تعديل الفوتر */
.full-width-bar_b {
    padding-bottom: 0 !important; /* التأكد من عدم وجود padding إضافي */
    margin-bottom: 0 !important; /* إزالة المسافة أسفل الفوتر */
}

/* إضافة التعديلات لعناصر الفوتر */
footer .text-center {
    margin-bottom: 0; /* إزالة أي مسافة إضافية أسفل النص */
    padding-bottom: 0; /* إزالة أي padding إضافي */
}

    /* الشريط العلوي */
    .full-width-bar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 65px;
      background-color: rgba(0, 0, 0, 0.6);
      z-index: 10;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 30px;
    }

    body,
    html {
      margin: 0;
      padding: 0;
      overflow-x: hidden;
    }

    .full-width-bar_b {
      margin-bottom: 0 !important;
      padding-bottom: 0 !important;
    }

    /* الشريط السفلي */
    .full-width-bar_b {
      bottom: 0;
      left: 0;
      right: 0;
      height: 300px;
      background-color: rgba(0, 0, 0, 0.2);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0;
    }

    /* زر التسجيل في الشريط العلوي */
    .register-btn {
      background-color: #ff7f00;
      padding: 10px 20px;
      border-radius: 5px;
      color: white;
      font-weight: bold;
      text-decoration: none;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .register-btn:hover {
      background-color: #ff4c00;
      transition: background-color 0.3s ease;
    }
  </style>
  </head>

  <body class="font-['Montserrat'] bg-gray-50">
            <!-- الشعار في الشريط العلوي -->
            <div class="full-width-bar">
              <img src="/images/logo.svg" alt="شعار" class="logo-animation w-[70px]" id="logo" />
              <a href="/login" class="register-btn">التسجيل</a>
            </div>
        {{-- <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 p-6"> --}}
        {{-- <div class="max-w-7xl w-full bg-white/70 dark:bg-white/10 backdrop-blur-xl border border-gray-200 dark:border-white/20 rounded-3xl p-10 shadow-xl transition-all"> --}}

        <!-- قسم البطاقة التعريفية -->
        <section class="relative bg-hero">
            <div class="absolute inset-0 bg-hero-overlay flex items-center justify-center">
                <div class="text-center text-white max-w-2xl px-4">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 text-gradient fade-in-title text-orange-600">نظام
                        إدارة المخازن - مدار</h1>
                    <p class="mb-6 text-lg">بسّط عملياتك مع تتبع وتحليلات المخزون الفورية والقوية.</p>
                    <a href="#features"
                        class="bg-orange-600 hover:bg-orange-500 text-white font-semibold py-3 px-6 rounded-lg shadow-md btn-hover">استكشاف
                        الميزات</a>
                </div>
            </div>
        </section>

        <!-- بقية الأقسام كما هي -->
        <section class="py-16 bg-white text-center">
            <div class="max-w-4xl mx-auto px-4">
                <h2 class="text-3xl font-bold mb-4 text-gray-800">مرحبًا بك في مستقبل التحكم في المخزون</h2>
                <p class="text-gray-600">نظامنا يمكّن الأقسام والمديرين من أدوات سهلة لتتبع وإدارة وتحليل بيانات المخزن
                    في واجهة حديثة.</p>
            </div>
        </section>

        <section id="features" class="py-16 bg-gray-100">
            <div class="max-w-6xl mx-auto px-4 grid md:grid-cols-3 gap-8">
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden hover-card shadow-custom">
                    <img src="/images/warehouse.jpg" alt="مستودع" class="w-full h-48 object-cover" />
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 text-gray-800">نظرة عامة على المستودع</h3>
                        <p class="text-gray-600">احصل على صورة كاملة لمستويات المخزون، واستخدام المساحة، والمقاييس
                            الرئيسية.</p>
                    </div>
                </div>
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden hover-card shadow-custom">
                    <img src="/images/tracking.jpg" alt="تتبع" class="w-full h-48 object-cover" />
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 text-gray-800">التتبع الفوري</h3>
                        <p class="text-gray-600">راقب حركة العناصر والكميات والأقسام في الوقت الفعلي.</p>
                    </div>
                </div>
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden hover-card shadow-custom">
                    <img src="/images/analytics.jpg" alt="تحليلات" class="w-full h-48 object-cover" />
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 text-gray-800">لوحة تحكم التحليلات</h3>
                        <p class="text-gray-600">تصوّر عملياتك مع تقارير ورسوم بيانية قابلة للتخصيص.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- قسم الدعوة للعمل -->
        <section class="py-20 bg-gradient-orange text-white text-center">
            <h2 class="text-3xl font-bold mb-4">ابدأ في إدارة المخزون بشكل أذكى اليوم</h2>
            <p class="mb-6">سجل الآن لتحويل عملية المخزون الخاصة بك مع حلنا الشامل.</p>
            <a href="/login"
                class="bg-white text-orange-600 font-semibold px-6 py-3 rounded-lg shadow-md hover:bg-gray-200 btn-hover">ابدأ
                الآن</a>
        </section>


        <footer class="full-width-bar_b bg-white dark:bg-gray-900">
          <div class="mx-auto w-full max-w-screen-xl p-4 pt-6 lg:pt-8">
              <div class="md:flex md:justify-between">
                  <div class="mb-6 md:mb-0">
                      <a href="/" class="flex items-center">
                          <img src="/images/logo.svg" class="h-8 me-3" alt="Madar Logo" />
                          <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">مدار</span>
                      </a>
                  </div>
                  <div class="flex m-1 space-x-2">
                      <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                          <i class="fab fa-facebook-f"></i>
                      </a>
                      <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                          <i class="fab fa-discord"></i>
                      </a>
                      <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                          <i class="fab fa-twitter"></i>
                      </a>
                      <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                          <i class="fab fa-github"></i>
                      </a>
                      <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                          <i class="fab fa-dribbble"></i>
                      </a>
                  </div>
              </div>
              <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
              <div class="sm:flex sm:items-center sm:justify-between">
                  <div>
                      <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">
                          © 2025 <a href="/" class="hover:underline">مدار™</a>. جميع الحقوق محفوظة.
                      </span>
                  </div>
              </div>
              <div class="text-center mt-0">
                  <img src="/images/azal_university_logo.svg" alt="شعار جامعة أزال" class="mx-auto w-28 mb-4">
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                      هذا المشروع تم إنجازه كجزء من متطلبات التخرج من كلية تكنولوجيا المعلومات  (CIS,MIS,CS)،<br>
                      تحت إشراف جامعة أزال للعلوم والتكنولوجيا. 
                      نعبر عن خالص امتناننا للدعم الأكاديمي والمساندة المقدمة من قبل الجامعة والمشرف.
                  </p>
              </div>
          </div>
      </footer>
      
        
        

        <!-- إضافة الجافا سكريبت -->
        <script>
            // إعادة تشغيل الحركة عند الضغط على الشعار
            const logo = document.getElementById('logo');

            logo.addEventListener('click', () => {
                // إضافة فصل الحركة الحالية
                logo.classList.remove('logo-animation');
                // إعادة تحميل الحركة
                void logo.offsetWidth; // Force reflow
                logo.classList.add('logo-animation');
            });
        </script>
    </body>
    {{-- </div>
</div> --}}
</x-home>
