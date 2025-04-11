<x-layout>
    <!-- Modal -->
    <div class="modal fade" id="roleWarehouseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <x-title :title="' إضافة/تعديل دور لمستودع'" />
                    <button type="button" id="closeModalBtn" style="display: none;">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="roleWarehouseForm">
                        @csrf
                        <div x-data="{ open: true }">
                            <!-- زر لفتح أو إغلاق القسم -->
                            <button type="button" @click="open = !open" class="text-indigo-600 hover:text-indigo-700 mb-2 ml-4">
                                <span
                                    x-html="open ? '<i class=\'fa-solid fa-magnifying-glass-minus fa-lg\'></i>' :'<i class=\'fa-solid fa-magnifying-glass-plus fa-lg\'></i>'">
                                </span>
                            </button>
                
                
                            <!-- الحقول القابلة للطي -->
                            <div x-show="open" x-transition>
                        <div class="grid grid-cols-3 gap-4">
                        <input type="hidden" id="roleWarehouseId">
                        <div class="form-group">
                            <label for="role_id"
                            class="block text-sm font-medium text-gray-600 dark:text-gray-400">الدور</label</label>
                            <select id="role_id" class="form-control w-full bg-gray-100 rounded border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-2 px-4 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 mt-1 ">

                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="warehouse_id"
                            class="block text-sm font-medium text-gray-600 dark:text-gray-400">المستودع</label>
                            <select id="warehouse_id" class="form-control w-full bg-gray-100 rounded border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-2 px-4 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 mt-1 ">
                                
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="branch_id"
                                        class="block text-sm font-medium text-gray-600 dark:text-gray-400">الفرع</label>
                            <select id="branch_id" class="form-control w-full bg-gray-100 rounded border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-2 px-4 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 mt-1 ">

                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-button class="btn btn-success">حفظ </x-button>
                        {{-- <button type="submit" class="btn btn-success">حفظ</button> --}}
                    </div>
                </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="container">
        
        <button class="btn btn-primary my-3 " id="addNewBtn"  style="display: none;">إضافة جديدة</button>
        
        <div class="overflow-x-auto mt-1">
            <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">الدور</th>
                        <th class="px-4 py-3">المستودع</th>
                        <th class="px-4 py-3">الفرع</th>
                        <th class="px-4 py-3"h>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roleWarehouses as $rw)
                <tr class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                    
                    <td class="px-6 py-4">{{ $rw->role->name }}</td>
                    <td class="px-6 py-4">{{ $rw->warehouse->name }}</td>
                    <td class="px-6 py-4">{{ $rw->branch->name }}</td>
                    <td class="px-6 py-4">
                        <button class="btn btn-warning editBtn" data-id="{{ $rw->id }}">تعديل</button>
                        <button class="btn btn-danger deleteBtn" data-id="{{ $rw->id }}">حذف</button>
                       
                    
                      
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
    <div class="mt-4">
        <x-pagination-links :paginator="$roleWarehouses" />

    </div>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("roleWarehouseModal");
        const closeModalBtn = document.getElementById("closeModalBtn");
        const addNewBtn = document.getElementById("addNewBtn");
        const form = document.getElementById("roleWarehouseForm");
        
        addNewBtn.addEventListener("click", () => {
            modal.style.display = "block";
        });
        
        closeModalBtn.addEventListener("click", () => {
            modal.style.display = "none";
        });
    
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const data = {
                role_id: document.getElementById("role_id").value,
                warehouse_id: document.getElementById("warehouse_id").value,
                branch_id: document.getElementById("branch_id").value,
                _token: "{{ csrf_token() }}"
            };
            
            const response = await fetch("{{ route('role-warehouse.store') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            });
            
            if (response.ok) {
                location.reload();
            }
        });
    
        document.querySelectorAll(".deleteBtn").forEach(button => {
            button.addEventListener("click", async () => {
                if (confirm("هل أنت متأكد أنك تريد حذف هذا السجل؟")) {
                    const id = button.getAttribute("data-id");
                    const response = await fetch(`{{ url('role-warehouse/delete') }}/${id}`, {
                        method: "DELETE",
                        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
                    });
                    if (response.ok) {
                        location.reload();
                    }
                }
            });
        });
    });
    </script>
    
</x-layout>

