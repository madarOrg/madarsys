{{-- inventory-transaction-list.blade --}}
{{-- resources\views\inventory\transactions\index.blade.php --}}
<x-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <!-- تمرير transactions كـ prop إلى Livewire -->

        <livewire:inventory-transaction-list-livewire :transactions="$transactions" />
        
    </section>
</x-layout>

