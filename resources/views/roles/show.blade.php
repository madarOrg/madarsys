<x-layout>
    <x-title :title="'تفاصيل الدور - ' . $role->name" />
    
    <div class="mt-4">
        <h2 class="text-lg font-semibold">المستخدمون الذين يملكون هذا الدور:</h2>
        <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400 border border-gray-300 rounded-lg mt-2">
            <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-3">اسم المستخدم</th>
                    <th class="px-4 py-3">البريد الإلكتروني</th>
                    <th class="px-4 py-3">تاريخ الانضمام</th>
                </tr>
            </thead>
            <tbody>
                @forelse($role->users as $user)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-300">{{ $user->name }}</td>
                    <td class="px-4 py-3">{{ $user->email }}</td>
                    <td class="px-4 py-3">{{ $user->created_at->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center p-4">لا يوجد مستخدمون لهذا الدور</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <x-button :href="route('roles.index')" type="button" class="mt-4">
        العودة إلى قائمة الأدوار
    </x-button>
</x-layout>
