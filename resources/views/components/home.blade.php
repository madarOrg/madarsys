<x-base class="w-full px-0 py-0 mx-0 mt-0">
  {{-- class="flex items-center justify-between w-full px-0 pt-1 pb-5 mx-auto flex-wrap-inherit" --}}
    <div class="grid grid-rows-[auto_1fr_auto] h-screen">
        <!-- Sidebar: شريط علوي وقائمة جانبية -->
        {{-- <x-navbar class="lg:row-span-3 lg:col-span-1 bg-gray-800 text-white  dark:bg-gray-900 dark:text-white" /> --}}

        <!-- المحتوى الرئيسي -->
        <main 
        class="   dark:bg-gray-900 dark:text-white overflow-y-auto
        bg-linear-to-r/srgb from-indigo-500 to-teal-400
        "
        >
            {{ $slot }}
        </main>

        <!-- الفوتر -->
        {{-- <x-footer  --}}
        {{-- class=" row-span-full col-span-full bg-gray-800 text-white text-center dark:bg-gray-900 dark:text-white " --}}
         {{-- /> --}}
    </div>
</x-base>