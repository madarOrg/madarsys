<x-base>
    <div class="grid grid-rows-[auto_1fr_auto] min-h-screen">
        <!-- Sidebar: شريط علوي وقائمة جانبية -->
        <x-navbar 
            class="bg-gray-800 text-white dark:bg-gray-900 dark:text-white"
        />

        <!-- Main Content -->
        <main 
            class="px-4 sm:px-6 md:px-8 lg:pl-2 dark:bg-gray-900 dark:text-white"
        >
            <x-alert />
            {{ $slot }}
        </main>

        <!-- Footer -->
        <x-footer 
            class="bg-gray-800 text-white text-center dark:bg-gray-900 dark:text-white"
        />
    </div>
</x-base>
