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
            background: url('/storage/images/dashboard.jpg') no-repeat center center/cover;
            min-height: 60vh;
            position: relative;
            overflow: hidden;
        }

        .bg-hero-overlay {
            background: rgba(0, 0, 0, 0.6);
            /* position: absolute; */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        /* تأثير الحركة للشعار عند تحميل الصفحة */
        .logo-animation {
            position: absolute;
            top: 10px;
            /* ازاحة الشعار قليلا الى الداخل */
            left: 10px;
            /* ازاحة الشعار قليلا الى الداخل */
            width: 50px;
            /* تقليص حجم الشعار */
        }

        /* تعديل الفوتر */
        .full-width-bar_b {
            padding-bottom: 0 !important;
            /* التأكد من عدم وجود padding إضافي */
            margin-bottom: 0 !important;
            /* إزالة المسافة أسفل الفوتر */
        }

        /* إضافة التعديلات لعناصر الفوتر */
        footer .text-center {
            margin-bottom: 0;
            /* إزالة أي مسافة إضافية أسفل النص */
            padding-bottom: 0;
            /* إزالة أي padding إضافي */
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
    {{-- </head> --}}

    <body class="font-['Montserrat'] bg-gray-50">
        <!-- الشعار في الشريط العلوي -->
        <div class="full-width-bar">
            <img src="/storage/images/logo.svg" alt="شعار" class="logo-animation w-[70px]" id="logo" />
            <a href="/login" class="register-btn">التسجيل</a>
        </div>
        {{-- <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 p-6"> --}}
        {{-- <div class="max-w-7xl w-full bg-white/70 dark:bg-white/10 backdrop-blur-xl border border-gray-200 dark:border-white/20 rounded-3xl p-10 shadow-xl transition-all"> --}}

        <!-- قسم البطاقة التعريفية -->
        <section class="relative bg-hero min-h-screen">
            <div class="absolute  bg-hero-overlay flex items-center justify-center overflow-hidden">
                <div class="text-center text-white max-w-2xl px-4">
            
                    <h1 class="mx-auto max-w-4xl font-display text-5xl font-bold tracking-normal text-orange-600 dark:text-orange-100 sm:text-7xl">
                        نظام إدارة المخزون
                        <span class="text-orange-500 dark:text-orange-200">مدار</span>
                    </h1>
            
                    <div class="mb-6 text-lg mt-4">
                        <p>بسّط عملياتك مع تتبع وتحليلات المخزون الفورية والقوية.</p>
                    </div>
            
                    <!-- الزخرفة -->
                    <div class="relative h-10">
                        <svg aria-hidden="true" viewBox="0 0 418 42"
                            class=" top-0 left-0 h-full w-full fill-orange-600 dark:fill-orange-400/60 pointer-events-none z-0"
                            preserveAspectRatio="none">
                            <path fill-orange-600
                                d="M203.371.916c-26.013-2.078-76.686 1.963-124.73 9.946L67.3 12.749C35.421 18.062 18.2 21.766 6.004 25.934 1.244 27.561.828 27.778.874 28.61c.07 1.214.828 1.121 9.595-1.176 9.072-2.377 17.15-3.92 39.246-7.496C123.565 7.986 157.869 4.492 195.942 5.046c7.461.108 19.25 1.696 19.17 2.582-.107 1.183-7.874 4.31-25.75 10.366-21.992 7.45-35.43 12.534-36.701 13.884-2.173 2.308-.202 4.407 4.442 4.734 2.654.187 3.263.157 15.593-.780 35.401-2.686 57.944-3.488 88.365-3.143 46.327.526 75.721 2.23 130.788 7.584 19.787 1.924 20.814 1.98 24.557 1.332l.066-.011c1.201-.203 1.53-1.825.399-2.335-2.911-1.31-4.893-1.604-22.048-3.261-57.509-5.556-87.871-7.36-132.059-7.842-23.239-.254-33.617-.116-50.627.674-11.629.540-42.371 2.494-46.696 2.967-2.359.259 8.133-3.625 26.504-9.810 23.239-7.825 27.934-10.149 28.304-14.005 .417-4.348-3.529-6-16.878-7.066Z">
                            </path>
                        </svg>
                    </div>
            
                    <!-- الزر -->
                    <div class="mt-12 relative z-10">
                        <a href="#features"
                            class="bt-2 bg-orange-600 hover:bg-orange-500 text-white font-semibold py-3 px-6 rounded-lg shadow-md btn-hover">
                            استكشاف الميزات
                        </a>
                    </div>
            
                </div>
            </div>
            

            <!-- شعار الجامعة (اختياري) -->
            <img src="/storage/images/azal_university_logo.svg" alt="شعار الجامعة" class="mx-auto mb-6 w-20 h-20">

        </section>

        <!-- بقية الأقسام كما هي -->
        <div class="py-16 bg-white text-center">
            <!-- عبارة تسويقية أو جملة تعريفية (يمكن تعديلها أو إزالتها) -->
            <a href=""
                class="border border-orange-500 dark:border-orange-300 rounded-lg py-2 px-4 text-orange-600 dark:text-orange-200 text-sm mb-5 transition duration-300 ease-in-out hover:text-orange-700 dark:hover:text-orange-100">
                تجربة تخرج مميزة مع نظام إدارة المخزون
            </a>
            <!-- وصف مختصر أو جملة تعريفية عن المشروع -->
            <h2
                class="mx-auto mt-12 max-w-xl text-lg sm:text-orange-500 text-orange-700 dark:text-orange-200 leading-7">
                تم إنجاز هذا المشروع كجزء من متطلبات التخرج من كلية تكنولوجيا المعلومات (CIS، MIS، CS).
            </h2>
            <div class="max-w-4xl mx-auto px-4">
                <h2 class="text-3xl font-bold mb-4 text-gray-800">مرحبًا بك في مستقبل التحكم في المخزون</h2>
                <p class="text-gray-600">نظامنا يمكّن الأقسام والمديرين من أدوات سهلة لتتبع وإدارة وتحليل بيانات المخزن
                    في واجهة حديثة.</p>
            </div>
        </div>

        <div id="features" class="py-16 bg-gray-100">
            <div class="max-w-6xl mx-auto px-4 grid md:grid-cols-3 gap-8">
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden hover-card shadow-custom">
                    <img src="/storage/images/warehouse.jpg" alt="مستودع" class="w-full h-48 object-cover" />
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 text-gray-800">نظرة عامة على المستودع</h3>
                        <p class="text-gray-600">احصل على صورة كاملة لمستويات المخزون، واستخدام المساحة، والمقاييس
                            الرئيسية.</p>
                    </div>
                </div>
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden hover-card shadow-custom">
                    <img src="/storage/images/tracking.jpg" alt="تتبع" class="w-full h-48 object-cover" />
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 text-gray-800">التتبع الفوري</h3>
                        <p class="text-gray-600">راقب حركة العناصر والكميات والأقسام في الوقت الفعلي.</p>
                    </div>
                </div>
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden hover-card shadow-custom">
                    <img src="/storage/images/analytics.jpg" alt="تحليلات" class="w-full h-48 object-cover" />
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 text-gray-800">لوحة تحكم التحليلات</h3>
                        <p class="text-gray-600">تصوّر عملياتك مع تقارير ورسوم بيانية قابلة للتخصيص.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- قسم الدعوة للعمل -->
        <div class="py-20 bg-gradient-orange text-black text-center">
            <h2 class="text-3xl font-bold mb-4">ابدأ في إدارة المخزون بشكل أذكى اليوم</h2>
            <p class="mb-6">سجل الآن لتحويل عملية المخزون الخاصة بك مع حلنا الشامل.</p>
            <a href="/login"
                class="bg-gray-200 text-orange-600 font-semibold px-6 py-3 rounded-lg shadow-md hover:bg-gray-200 btn-hover">ابدأ
                الآن</a>
        </div>


        <!-- قسم شهادات المستخدمين -->
        <div class="py-5 dark:bg-gray-900">
            <div class="container flex flex-col items-center justify-center w-full p-6 mx-auto text-center xl:px-0">
                <img src="/storage/images/madarMarkting.gif" alt="شعار" loading="lazy" />

                <div
                    class="flex flex-col items-center justify-center text-sm font-bold tracking-wider text-indigo-600 uppercase">
                    شهادات المستخدمين</div>
                <h2
                    class="flex flex-col items-center justify-center max-w-2xl mt-3 text-xl font-bold leading-snug tracking-tight text-gray-800 lg:leading-tight lg:text-4xl dark:text-white">
                    ماذا يقول مستخدمونا</h2>
            </div>

            <div class="container p-6 text-xsm mx-auto mb-5 xl:px-0">
                <div class="grid gap-4 lg:grid-cols-3 xl:grid-cols-3">
                    <!-- شهادة 1 -->
                    <div class="flex justify-between items-center lg:col-span-3 xl:col-auto">
                        {{-- <div class="flex-shrink-0 overflow-hidden rounded-full w-10 h-10">
                            <img src="/storage/images/comp1.gif" alt="شعار" />
                        </div> --}}
                        <div
                            class="flex flex-col justify-between px-3 py-3 bg-gray-100 dark:bg-gray-800 md:px-14 rounded-2xl md:py-14 dark:bg-trueGray-800">
                            <p class="text-xl leading-normal dark:text-gray-300">"نظام مدار سهّل علينا تتبع المنتجات
                                والطلبات بدقة كبيرة. واجهته سهلة وبديهية."</p>
                            <div class="flex items-center mt-6 space-x-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden">
                                    <img alt="مستخدم" src="/storage/images/user1.jpg" loading="lazy"
                                        class="w-full h-full object-cover" />
                                </div>
                                <div>
                                    <div class="text-lg font-medium text-gray-600 dark:text-gray-400">أحمد العزي</div>
                                    <div class="text-gray-600 dark:text-gray-400">مدير مخزن - شركة الراقي</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- شهادة 2 -->
                    <div>
                        <div
                            class="flex flex-col justify-between  px-3 py-3 bg-gray-100 dark:bg-gray-800 md:px-14 rounded-2xl md:py-14 dark:bg-trueGray-800">
                            {{-- <div class="flex-shrink-0 overflow-hidden rounded-full w-10 h-10 mb-4">
                                <img src="/storage/images/comp1.gif" alt="شعار" />
                            </div> --}}
                            <p class="text-xl leading-normal dark:text-gray-300">"التحليلات والتقارير ساعدتنا في اتخاذ
                                قرارات أفضل وزادت من كفاءة العمليات."</p>
                            <div class="flex items-center mt-6 space-x-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden">
                                    <img alt="مستخدم" src="/storage/images/user2.jpg" loading="lazy"
                                        class="w-full h-full object-cover" />
                                </div>
                                <div>
                                    <div class="text-lg font-medium text-gray-600 dark:text-gray-400">لمياء الحاج</div>
                                    <div class="text-gray-600 dark:text-gray-400">محللة نظم - مؤسسة التقنية</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- شهادة 3 -->
                    <div>
                        <div
                            class="flex flex-col justify-between  px-3 py-3 bg-gray-100 dark:bg-gray-800 md:px-14 rounded-2xl md:py-14 dark:bg-trueGray-800">
                            <p class="text-xl leading-normal dark:text-gray-300">"استخدمت العديد من الأنظمة، ولكن مدار
                                كان الأكثر تنظيمًا وسرعة في الأداء."</p>
                            <div class="flex items-center mt-6 space-x-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden">
                                    <img alt="مستخدم" src="/storage/images/user3.jpg" loading="lazy"
                                        class="w-full h-full object-cover" />
                                </div>
                                <div>
                                    <div class="text-lg font-medium text-gray-600 dark:text-gray-400">زيد الشامي</div>
                                    <div class="text-gray-600 dark:text-gray-400">مشرف لوجستي - مجموعة النخبة</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- قسم أعضاء الفريق -->
        <footer class=" bg-white dark:bg-gray-900">
        <div id="team" class="py-20 bg-white dark:bg-gray-900 text-center">
            <h2 class="text-4xl font-bold text-orange-600 dark:text-orange-300 mb-10">فريق العمل</h2>
            <div class="max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10 px-4">
                <!-- عضو الفريق 1 -->
                <div
                    class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition duration-300 hover-card">
                    <img src="/storage/images/emad.jpg" alt="عضو الفريق"
                        class="w-28 h-28 mx-auto rounded-full mb-4 shadow-md object-cover">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">د.عماد العزعزي</h3>
                    {{-- <p class="text-orange-500 dark:text-orange-300">قائد الفريق</p> --}}
                </div>
                <div class=" p-6  hover:shadow-xl transition duration-300 hover-card">
                    <img src="/storage/images/comp4.gif" alt="عضو الفريق"
                        class="w-28 h-28 mx-auto rounded-full mb-4 shadow-md object-cover">
                    {{-- <h3 class="text-xl font-semibold text-gray-800 dark:text-white">لمياء الحميري</h3> --}}
                    {{-- <p class="text-orange-500 dark:text-orange-300">قائد الفريق</p> --}}
                </div>

                <!-- عضو الفريق 1 -->
                <div
                    class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition duration-300 hover-card">
                    <img src="/storage/images/lamya.jpg" alt="عضو الفريق"
                        class="w-28 h-28 mx-auto rounded-full mb-4 shadow-md object-cover">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">لمياء الحميري</h3>
                    {{-- <p class="text-orange-500 dark:text-orange-300">قائد الفريق</p> --}}
                </div>
                <!-- عضو الفريق 2 -->
                <div
                class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition duration-300 hover-card">
                <img src="/storage/images/basma.jpg" alt="عضو الفريق"
                        class="w-28 h-28 mx-auto rounded-full mb-4 shadow-md object-cover">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">بسمة غانم</h3>
                    {{-- <p class="text-orange-500 dark:text-orange-300">مطورة واجهات</p> --}}
                </div>
                <div class=" p-6  hover:shadow-xl transition duration-300 hover-card">
                    <img src="/storage/images/comp6.gif" alt="عضو الفريق"
                        class="w-28 h-28 mx-auto rounded-full mb-4 shadow-md object-cover">
                    {{-- <h3 class="text-xl font-semibold text-gray-800 dark:text-white">لمياء الحميري</h3> --}}
                    {{-- <p class="text-orange-500 dark:text-orange-300">قائد الفريق</p> --}}
                </div>
                <!-- عضو الفريق 3 -->
                <div
                    class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition duration-300 hover-card">
                    <img src="/storage/images/esraa.jpg" alt="عضو الفريق"
                        class="w-28 h-28 mx-auto rounded-full mb-4 shadow-md object-cover">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">اسراء الشميري</h3>
                    {{-- <p class="text-orange-500 dark:text-orange-300">مبرمج باك إند</p> --}}
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition duration-300 hover-card">
                    <img src="/storage/images/wfa.jpg" alt="عضو الفريق"
                        class="w-28 h-28 mx-auto rounded-full mb-4 shadow-md object-cover">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">وفاء الحمزي</h3>
                    {{-- <p class="text-orange-500 dark:text-orange-300">مبرمج باك إند</p> --}}
                </div>
                <div class=" p-6  hover:shadow-xl transition duration-300 hover-card">
                    <img src="/storage/images/comp7.gif" alt="عضو الفريق"
                        class="w-28 h-28 mx-auto rounded-full mb-4 shadow-md object-cover">
                    {{-- <h3 class="text-xl font-semibold text-gray-800 dark:text-white">لمياء الحميري</h3> --}}
                    {{-- <p class="text-orange-500 dark:text-orange-300">قائد الفريق</p> --}}
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition duration-300 hover-card">
                    <img src="/storage/images/kawther.jpg" alt="عضو الفريق"
                        class="w-28 h-28 mx-auto rounded-full mb-4 shadow-md object-cover">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">كوثر العشاري</h3>
                    {{-- <p class="text-orange-500 dark:text-orange-300">مبرمج باك إند</p> --}}
                </div>

            </div>

        </div>

        
            <!-- component -->
            <!-- Foooter -->
            <div class="bg-white">
                <div class="max-w-screen-xl px-4 py-12 mx-auto space-y-8 overflow-hidden sm:px-6 lg:px-8">
                    <nav class="flex flex-wrap justify-center -mx-5 -my-2">
                        <div class="px-5 py-2">
                            <a href="#" class="text-base leading-6 text-gray-500 hover:text-gray-900">
                                ماذا عنا
                            </a>
                        </div>
                        <div class="px-5 py-2">
                            <a href="#" class="text-base leading-6 text-gray-500 hover:text-gray-900">
                                مدونة
                            </a>
                        </div>
                        <div class="px-5 py-2">
                            <a href="#team" class="text-base leading-6 text-gray-500 hover:text-gray-900">
                                الفريق
                            </a>
                        </div>
                        <div class="px-5 py-2">
                            <a href="#" class="text-base leading-6 text-gray-500 hover:text-gray-900">
                                الأسعار
                            </a>
                        </div>
                        <div class="px-5 py-2">
                            <a href="#" class="text-base leading-6 text-gray-500 hover:text-gray-900">
                                تواصل معنا
                            </a>
                        </div>
                        <div class="px-5 py-2">
                            <a href="#" class="text-base leading-6 text-gray-500 hover:text-gray-900">
                                الشروط و الأحكام
                            </a>
                        </div>
                    </nav>
                    <div class="flex justify-center mt-8 space-x-6">
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Facebook</span>
                            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Instagram</span>
                            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Twitter</span>
                            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84">
                                </path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">GitHub</span>
                            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Dribbble</span>
                            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c5.51 0 10-4.48 10-10S17.51 2 12 2zm6.605 4.61a8.502 8.502 0 011.93 5.314c-.281-.054-3.101-.629-5.943-.271-.065-.141-.12-.293-.184-.445a25.416 25.416 0 00-.564-1.236c3.145-1.28 4.577-3.124 4.761-3.362zM12 3.475c2.17 0 4.154.813 5.662 2.148-.152.216-1.443 1.941-4.48 3.08-1.399-2.57-2.95-4.675-3.189-5A8.687 8.687 0 0112 3.475zm-3.633.803a53.896 53.896 0 013.167 4.935c-3.992 1.063-7.517 1.04-7.896 1.04a8.581 8.581 0 014.729-5.975zM3.453 12.01v-.26c.37.01 4.512.065 8.775-1.215.25.477.477.965.694 1.453-.109.033-.228.065-.336.098-4.404 1.42-6.747 5.303-6.942 5.629a8.522 8.522 0 01-2.19-5.705zM12 20.547a8.482 8.482 0 01-5.239-1.8c.152-.315 1.888-3.656 6.703-5.337.022-.01.033-.01.054-.022a35.318 35.318 0 011.823 6.475 8.4 8.4 0 01-3.341.684zm4.761-1.465c-.086-.52-.542-3.015-1.659-6.084 2.679-.423 5.022.271 5.314.369a8.468 8.468 0 01-3.655 5.715z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </div>
                    <p class="mt-8 text-base leading-6 text-center text-gray-400">
                        © 2025 مدار, Inc. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>




    </body>

</x-home>
