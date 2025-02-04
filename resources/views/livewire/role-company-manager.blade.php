
<div class="p-6">
    <h2 class="text-xl font-semibold mb-4">إدارة الشركات والأدوار</h2>

    <div class="grid grid-cols-3 gap-4">
        @foreach ($roles as $role)
            <div class="border rounded-lg p-4 bg-gray-100">
                <h3 class="font-bold text-lg mb-2">{{ $role->name }}</h3>

                <div class="bg-white rounded p-2 shadow-inner">
                    <h4 class="text-sm font-semibold mb-2">الشركات المرتبطة:</h4>
                    @foreach ($role->companies as $company)
                        <div class="flex items-center justify-between mb-2">
                            <span>{{ $company->name }}</span>
                            <button wire:click="removeCompanyFromRole({{ $role->id }}, {{ $company->id }})" class="text-red-500 hover:text-red-700">
                                إزالة
                            </button>
                        </div>
                    @endforeach
                </div>

                <div
                    class="mt-4 border-dashed border-2 border-gray-300 p-4"
                    ondrop="handleDrop(event, {{ $role->id }})"
                    ondragover="allowDrop(event)"
                >
                    <p class="text-sm text-gray-500">اسحب وأفلت الشركات هنا</p>
                </div>
            </div>
        @endforeach
    </div>

    <h4 class="mt-8 text-lg font-semibold">قائمة الشركات المتاحة:</h4>
    <div class="grid grid-cols-3 gap-4 mt-4">
        @foreach ($companies as $company)
            <div
                class="p-2 border rounded bg-gray-50"
                draggable="true"
                ondragstart="drag(event, {{ $company->id }})"
            >
                {{ $company->name }}
            </div>
        @endforeach
    </div>

    <script>
        function allowDrop(event) {
            event.preventDefault();
        }

        function drag(event, companyId) {
            event.dataTransfer.setData('companyId', companyId);
        }

        function handleDrop(event, roleId) {
            event.preventDefault();
            const companyId = event.dataTransfer.getData('companyId');
            Livewire.emit('addCompanyToRole', roleId, companyId);
        }
    </script>
</div>
