<x-base>
    <div class="grid grid-rows-[auto_1fr_auto] min-h-screen">
        <x-navbar 
        {{-- class="bg-gray-800 text-white dark:bg-gray-900 dark:text-white" --}}
        />

        <!-- Main Content -->
        <main 
            class="px-4 sm:px-6 md:px-8 lg:pl-2 dark:bg-gray-900 dark:text-gray-400 "
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(productId) {
        Swal.fire({
            title: "هل أنت متأكد؟",
            text: "لن تتمكن من التراجع عن هذا!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "نعم، احذف!",
            cancelButtonText: "إلغاء"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + productId).submit();
            }
        });
    }
</script>

