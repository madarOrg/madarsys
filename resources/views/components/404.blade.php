<x-base>
    <div class="h-screen w-screen bg-white dark:bg-gray-700 flex items-center justify-center relative">

        <div class="container flex flex-col lg:flex-row items-center justify-between px-5 text-gray-700 relative">
            
            <!-- الصورة مع النص "Opps!" فوقها -->
            <div class="w-full lg:w-1/2 flex justify-center items-center relative">
                
                <!-- Opps فوق الصورة -->
                <div class="absolute top-0 text-center">
                    <div class="text-orange-500 font-extrabold leading-none" style="font-size: 8rem;">
                        Opps!
                    </div>
                </div>

                <!-- الصورة -->
                <img src="{{ asset('storage/images/404.gif') }}" alt="404" class="max-w-full h-auto mt-20">
            </div>

            <!-- النص -->
            <div class="w-full lg:w-1/2 px-8 mt-10 lg:mt-0">
                <div class="text-7xl text-orange-500 font-extrabold mb-8">404</div>
                <p class="text-2xl md:text-3xl font-light leading-normal mb-8">
                    عفوا... الصفحة التي تبحث عنها غير موجودة
                </p>
                <a href="/" class="px-5 py-3 inline-block text-sm font-medium shadow-2xl text-white transition-all duration-400 rounded-lg bg-orange-600 hover:bg-red-700">
                    العودة إلى الصفحة الرئيسية
                </a>
            </div>

        </div>
    </div>
</x-base>
