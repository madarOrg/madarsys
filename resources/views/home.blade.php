<x-home>
  <div class="font-[sans-serif] max-w-4xl max-md:max-w-md mx-auto pt-20">
    <div class="grid items-center md:gap-20 gap-15">
      <div class="max-md:order-1 max-md:text-center">
        <h2 class="text-gray-800 md:text-5xl text-3xl font-extrabold mb-4 md:!leading-[55px]">
          مرحبًا بك في مدار نظام إدارة المخازن متعدد المستودعات
        </h2>
        <p class="mt-5 text-base text-gray-500 leading-relaxed">
          يضع بين يديك الحل الأمثل لتنظيم وإدارة عمليات التخزين بكفاءة واحترافية
        </p>

        <!-- الأقسام مع الأيقونات الجديدة -->
        <div class="mt-10 flex flex-wrap gap-5">
          <!-- إدارة متعددة للمستودعات -->
          <div class="flex-1 flex items-start gap-3 p-4 border border-gray-300 rounded-lg">
            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zM5 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zM15 5a1 1 0 011 1v10a1 1 0 11-2 0V6a1 1 0 011-1z" />
            </svg>
            <div>
              <h3 class="font-bold text-gray-800">إدارة متعددة للمستودعات</h3>
              <p class="text-sm text-gray-600">تحكم شامل لكل مستودع على حدة أو جميع المستودعات معًا</p>
            </div>
          </div>

          <!-- تتبع المخزون بدقة -->
          <div class="flex-1 flex items-start gap-3 p-4 border border-gray-300 rounded-lg">
            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
              <path d="M16 6a4 4 0 11-8 0 4 4 0 018 0zm-4 6a6 6 0 00-5.197 2.963A9.953 9.953 0 0010 18a9.953 9.953 0 001.197-3.037A6 6 0 0010 12z" />
            </svg>
            <div>
              <h3 class="font-bold text-gray-800">تتبع المخزون بدقة</h3>
              <p class="text-sm text-gray-600">تتبع كميات الأصناف وحالتها في الوقت الفعلي</p>
            </div>
          </div>

          <!-- تقارير وتحليلات متقدمة -->
          <div class="flex-1 flex items-start gap-3 p-4 border border-gray-300 rounded-lg">
            <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
              <path d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v10H5V5zm2 2a1 1 0 000 2h6a1 1 0 000-2H7z" />
            </svg>
            <div>
              <h3 class="font-bold text-gray-800">تقارير وتحليلات متقدمة</h3>
              <p class="text-sm text-gray-600">احصل على تقارير شاملة تساعدك في اتخاذ القرارات بسرعة وفعالية</p>
            </div>
          </div>

          <!-- إدارة العمليات اليومية بسهولة -->
          <div class="flex-1 flex items-start gap-3 p-4 border border-gray-300 rounded-lg">
            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
              <path d="M4 2a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H4zm3 6a1 1 0 112 0v4a1 1 0 11-2 0V8zm6 0a1 1 0 112 0v4a1 1 0 11-2 0V8z" />
            </svg>
            <div>
              <h3 class="font-bold text-gray-800">إدارة العمليات اليومية بسهولة</h3>
              <p class="text-sm text-gray-600">استلام المخزون، صرف الأصناف، والجرد الدوري</p>
            </div>
          </div>
        </div>

        <!-- زر تسجيل الدخول -->
        <form id="loginForm" class="space-y-4 md:space-y-6" action="{{ route('login') }}" method="get">
          <div class="mt-10 flex px-4 py-4 rounded-lg bg-gray-100 overflow-hidden">
            <button type="submit" class="w-full border border-gray-300 text-gray-900 bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
              تسجيل الدخول
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-home>
