<div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-11/12 md:w-1/2">
    @if (session('success'))
        <div class="relative bg-green-100 text-green-800 border border-green-400 p-6 rounded-lg shadow-xl mb-4 transform transition-all duration-500 ease-in-out opacity-100 scale-100">
            <!-- زر الإغلاق -->
            <button class="absolute top-2 left-2 text-green-600 hover:text-green-800 focus:outline-none transition duration-300" onclick="this.parentElement.style.display='none';">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <!-- محتوى الرسالة -->
            <div class="flex items-center space-x-4">
                <img src="{{ asset('storage/icons/success.png') }}" alt="Success Icon" class="h-8 w-8">
                <span class="text-lg font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="relative bg-red-100 text-red-800 border border-red-400 p-6 rounded-lg shadow-xl mb-4 transform transition-all duration-500 ease-in-out opacity-100 scale-100">
            <!-- زر الإغلاق -->
            <button class="absolute top-2 left-2 text-red-600 hover:text-red-800 focus:outline-none transition duration-300" onclick="this.parentElement.style.display='none';">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <!-- محتوى الرسالة -->
            <div class="flex items-center space-x-4">
                <img src="{{ asset('storage/icons/error.png') }}" alt="Error Icon" class="h-8 w-8">
                <span class="text-lg font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>
