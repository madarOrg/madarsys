<footer class="hide-on-print bg-white rounded-lg shadow-sm mb-0 dark:bg-gray-800 w-full px-0">
    <div class="w-full max-w-full p-4 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
        <!-- العمود الأول - حقوق الطبع -->
        <div class="text-sm text-gray-500 sm:text-base dark:text-gray-400 text-center md:text-left">
            <span>
                © {{ now()->year }}
                <a href="/" class="hover:underline">
                    {{ config('app.name', 'TutorNet') }}
                </a> All Rights Reserved.
            </span>
        </div>

        <!-- العمود الثاني - معلومات المشروع -->
        <div class="text-xxs text-gray-600 dark:text-gray-400 text-center md:text-center">
            <p>
                هذا المشروع تم إنجازه كجزء من متطلبات التخرج من كلية تكنولوجيا المعلومات (CIS, MIS, CS)،</br>
                تحت إشراف جامعة أزال للعلوم والتكنولوجيا-صنعاء.2024-2025
            </p>
        </div>
        <!-- العمود الثالث - الروابط والشعار -->
        <div
            class="grid grid-cols-1 md:grid-cols-3 items-center justify-items-center md:justify-items-end text-sm font-medium text-gray-500 dark:text-gray-400 gap-2 md:gap-4">
            <a href="/team" class="hover:underline">ماذا عنا</a>
            <a href="#" class="hover:underline">تواصل معنا</a>
            <a href="#">
                <img src="/storage/images/azal_university_logo.svg" alt="شعار جامعة أزال" class="w-8">
            </a>
        </div>

    </div>
</footer>
