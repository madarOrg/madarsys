<x-base>
    <div class="grid grid-rows-[auto_1fr_auto] h-screen"

    >
        <!-- Sidebar: شريط علوي وقائمة جانبية -->
        <x-navbar        
        {{-- class="lg:row-span-3 lg:col-span-1 bg-gray-800 text-white  dark:bg-gray-900 dark:text-white"  --}}

        />

        <!-- المحتوى الرئيسي -->
        <main 
        class="
        {{-- lg:pr-[267px] lg:pt-[94px]  --}}
        mt-24 px-4 sm:px-6 md:px-8 lg:pl-2 dark:bg-gray-900 dark:text-white"
        >
        <x-alert />

            {{ $slot }}
        </main>

        <!-- الفوتر -->
        <x-footer 
        {{-- class="row-span-1 col-span-2 bg-gray-800 text-white text-center  dark:bg-gray-900 dark:text-white "  --}}
        />
    </div>
</x-base>