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

                    <h1
                        class=" mx-auto max-w-4xl font-display text-xl font-bold tracking-normal text-orange-600 dark:text-orange-100 sm:text-7xl">
                        نظام الإدارة المتكاملة للمخازن و الشبكة المستودعية الذكية </br>
                        Integrated Inventory and Smart Warehouse Network Management System
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
                تم إنجاز هذا المشروع كجزء من متطلبات التخرج <br />
                من كلية تكنولوجيا المعلومات (CIS، MIS، CS).<br/>
                جامعة ازال للعلوم و التكنولوجيا - صنعاء
            </h2>
            <div class="max-w-4xl mx-auto px-4">
                <h2 class="text-3xl font-bold mb-4 text-gray-800">مرحبًا بك في مستقبل التحكم في المخزون</h2>
                <p class="text-gray-600">نظامنا يمكّن الأقسام و المدراء من أدوات سهلة لتتبع وإدارة وتحليل بيانات المخزن
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
                                    <div class="text-lg font-medium text-gray-600 dark:text-gray-400">أحمد </div>
                                    <div class="text-gray-600 dark:text-gray-400">مدير مخزن - شركة الراقي</div>
                                </div>
                            </div>
                            <div class=" p-6  hover:shadow-xl transition duration-300 hover-card">
                                <img src="/storage/images/comp4.gif" alt="عضو الفريق"
                                    class="w-28 h-28 mx-auto rounded-full mb-4 shadow-md object-cover">
                            </div>
                        </div>
                    </div>

                    <!-- شهادة 2 -->
                    <div>
                        <div
                            class="flex flex-col justify-between  px-3 py-3 bg-gray-100 dark:bg-gray-800 md:px-14 rounded-2xl md:py-14 dark:bg-trueGray-800">
                            <div class=" p-6  hover:shadow-xl transition duration-300 hover-card">
                                <img src="/storage/images/comp7.gif" alt=""
                                    class="w-28 h-28 mx-auto rounded-full mb-4 shadow-md object-cover">
                            </div>
                            <p class="text-xl leading-normal dark:text-gray-300">"التحليلات والتقارير ساعدتنا في اتخاذ
                                قرارات أفضل وزادت من كفاءة العمليات."</p>
                            <div class="flex items-center mt-6 space-x-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden">
                                    <img alt="مستخدم" src="/storage/images/user2.jpg" loading="lazy"
                                        class="w-full h-full object-cover" />
                                </div>
                                <div>
                                    <div class="text-lg font-medium text-gray-600 dark:text-gray-400">خديجة </div>
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
                                    <div class="text-lg font-medium text-gray-600 dark:text-gray-400">زيد</div>
                                    <div class="text-gray-600 dark:text-gray-400">مشرف لوجستي - مجموعة النخبة</div>
                                </div>
                            </div>
                            <div class=" p-6  hover:shadow-xl transition duration-300 hover-card">
                                <img src="/storage/images/comp6.gif" alt=""
                                    class="w-28 h-28 mx-auto rounded-full mb-4 shadow-md object-cover">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    <!-- قسم أعضاء الفريق والفوتر الكامل -->
<footer class="bg-white dark:bg-gray-900 mt-16 pt-10 border-t dark:border-gray-700">
    <!-- قسم فريق العمل -->
    <div id="team" class="text-center mb-10">
        <h2 class="text-3xl font-bold text-orange-600 dark:text-orange-300 mb-10">فريق العمل</h2>
        
        <!-- القائد -->
        <div class="flex justify-center mb-6">
            <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition duration-300 hover-card">
                <img src="/storage/images/img-emad.jpg" alt="عضو الفريق" class="w-28 h-28 mx-auto rounded-full mb-4 shadow-md object-cover">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">د.عماد العزعزي</h3>
            </div>
        </div>

        <!-- بقية الفريق -->
        <div class="flex flex-wrap justify-center gap-6 px-4">
            <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition duration-300 hover-card">
                <img src="/storage/images/lamya.jpg" alt="عضو الفريق" class="w-28 h-28 mx-auto rounded-full mb-4 shadow-md object-cover">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">لمياء الحميري</h3>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition duration-300 hover-card">
                <img src="/storage/images/esraa.jpg" alt="عضو الفريق" class="w-28 h-28 mx-auto rounded-full mb-4 shadow-md object-cover">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">إسراء الشميري</h3>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition duration-300 hover-card">
                <img src="/storage/images/wfa.jpg" alt="عضو الفريق" class="w-28 h-28 mx-auto rounded-full mb-4 shadow-md object-cover">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">وفاء الحمزي</h3>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition duration-300 hover-card">
                <img src="/storage/images/kawther.jpg" alt="عضو الفريق" class="w-28 h-28 mx-auto rounded-full mb-4 shadow-md object-cover">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">كوثر العشاري</h3>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition duration-300 hover-card">
                <img src="/storage/images/basma.jpg" alt="عضو الفريق" class="w-28 h-28 mx-auto rounded-full mb-4 shadow-md object-cover">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">بسمة غانم</h3>
            </div>
        </div>
    </div>

    <!-- روابط الفوتر -->
    <div class="max-w-screen-xl px-4 py-12 mx-auto space-y-8 sm:px-6 lg:px-8 border-t dark:border-gray-700">
        <nav class="flex flex-wrap justify-center gap-6 text-gray-500 dark:text-gray-300 text-base">
            <a href="#" class="hover:text-gray-900 dark:hover:text-white">ماذا عنا</a>
            <a href="#" class="hover:text-gray-900 dark:hover:text-white">مدونة</a>
            <a href="#team" class="hover:text-gray-900 dark:hover:text-white">الفريق</a>
            <a href="#" class="hover:text-gray-900 dark:hover:text-white">الأسعار</a>
            <a href="#" class="hover:text-gray-900 dark:hover:text-white">تواصل معنا</a>
            <a href="#" class="hover:text-gray-900 dark:hover:text-white">الشروط والأحكام</a>
        </nav>

        <!-- أيقونات التواصل الاجتماعي -->
        <div class="flex justify-center mt-8 space-x-6 rtl:space-x-reverse">
            <a href="#" class="text-gray-400 hover:text-gray-500 dark:hover:text-white" aria-label="Facebook">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M22 12a10 10 0 10-11.542 9.879V14.89h-2.54v-2.89h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988A10.002 10.002 0 0022 12z" clip-rule="evenodd" />
                </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-gray-500 dark:hover:text-white" aria-label="Instagram">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm10 2a3 3 0 013 3v10a3 3 0 01-3 3H7a3 3 0 01-3-3V7a3 3 0 013-3h10zm-5 3a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6zm4.5-2a1.5 1.5 0 100 3 1.5 1.5 0 000-3z"/>
                </svg>
            </a>
        </div>

        <!-- حقوق النشر -->
        <p class="text-center text-sm text-gray-400 dark:text-gray-500">
            © 2025 جميع الحقوق محفوظة  - مدار 
        </p>
    </div>
</footer>


    </body>

</x-home>
