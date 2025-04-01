{{-- <div class="relative mt-1 flex items-center">
    <!-- حقل البحث -->
    <input type="text" 
        id="{{ $id }}" 
        name="{{ $name }}" 
        placeholder="{{ $placeholder }}" 
        value="{{ $value }}"
        class="pr-10 bg-gray-100 rounded-lg border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-2 pl-64 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
    />

    <!-- أيقونة البحث -->
    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
        </svg>
    </div>
</div>
 --}}
 <div class="relative flex items-center w-full">
    <!-- حقل البحث -->
    <input type="text" 
        id="{{ $id }}" 
        name="{{ $name }}" 
        placeholder="{{ $placeholder }}" 
        value="{{ $value }}"
        class="pr-10 bg-gray-100 rounded-lg border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 
               hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-2 px-4 
               leading-8 transition-colors duration-200 ease-in-out 
               dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500
               w-full sm:w-80 md:w-96 lg:w-[400px]"  
    />

    <!-- أيقونة البحث -->
    <div class="absolute inset-y-0 right-3 flex items-center">
        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
        </svg>
    </div>
</div>
