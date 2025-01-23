<x-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col  px-4 py-4
 mx-auto sm:px-2 md:h-[calc(100vh-20px)]
 lg:py-0 overflow-hidden">
            
        
 {{-- <div 
 class="w-full bg-white border-gray-200 dark:border sm:max-w-sm md:max-w-md xl:max-w-lg dark:bg-gray-800 dark:border-gray-700"
 > --}}
    <div class="p-6 sm:p-8 space-y-4 md:space-y-6">
        <x-title :title="'إنشاء حساب'"></x-title>

        @if(session('success'))
            <div class="alert alert-success text-xs sm:text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger text-xs sm:text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="space-y-2 md:space-y-2" action="{{ route('users.store') }}" method="POST">
            @csrf

            <!-- حقل اسم المستخدم -->
            <x-file-input 
                id="name" 
                name="name" 
                type="text" 
                label="اسمك" 
                placeholder="اسم المستخدم" 
                required 
            />

            <!-- حقل البريد الإلكتروني -->
            <x-file-input 
                id="email" 
                name="email" 
                type="email" 
                label="البريد الإلكتروني" 
                placeholder="name@company.com" 
                required 
            />

            <!-- حقل كلمة المرور -->
            <x-file-input 
                id="password" 
                name="password" 
                type="password" 
                label="كلمة المرور" 
                placeholder="••••••••" 
                required 
            />

            <!-- حقل تأكيد كلمة المرور -->
            <x-file-input 
                id="password_confirmation" 
                name="password_confirmation" 
                type="password" 
                label="تأكيد كلمة المرور" 
                placeholder="••••••••" 
                required 
            />

            <!-- قائمة اختيار الدور -->
<x-select-dropdown 
id="role" 
name="role" 
label="اختر الدور"
:options="$roles->pluck('name', 'name')->toArray()"
:selected="old('role', $user->role ?? '')" 
/>

<!-- قائمة اختيار حالة المستخدم -->
<x-select-dropdown 
id="status" 
name="status" 
label="حالة المستخدم"
:options="['active' => 'فعال', 'inactive' => 'موقوف', 'pending' => 'قيد الانتظار']"
:selected="old('status', $user->status ?? '')"
required 
/>

           <!-- زر إرسال -->
           <div class="flex justify-end px-6 ">
<x-button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700">
    إنشاء حساب
</x-button>
</div>
        </form>
    </div>
</div>

        </div>
    </section>
</x-layout>
