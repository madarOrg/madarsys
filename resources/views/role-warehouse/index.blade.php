<x-layout>
   
    <div class="container">
        <h2>إدارة الأدوار والمستودعات</h2>
        <button class="btn btn-primary my-3" id="addNewBtn">إضافة جديدة</button>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>الدور</th>
                    <th>المستودع</th>
                    <th>الفرع</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roleWarehouses as $rw)
                <tr>
                    <td>{{ $rw->role->name }}</td>
                    <td>{{ $rw->warehouse->name }}</td>
                    <td>{{ $rw->branch->name }}</td>
                    <td>
                        <button class="btn btn-warning editBtn" data-id="{{ $rw->id }}">تعديل</button>
                        <button class="btn btn-danger deleteBtn" data-id="{{ $rw->id }}">حذف</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="roleWarehouseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة/تعديل دور لمستودع</h5>
                    <button type="button" class="close" id="closeModalBtn">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="roleWarehouseForm">
                        @csrf
                        <input type="hidden" id="roleWarehouseId">
                        <div class="form-group">
                            <label>الدور</label>
                            <select id="role_id" class="form-control">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>المستودع</label>
                            <select id="warehouse_id" class="form-control">
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>الفرع</label>
                            <select id="branch_id" class="form-control">
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">حفظ</button>
                    </form>
                </div>
            </div>
        </div>
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

