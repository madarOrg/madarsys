<x-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col  px-4 py-4
 mx-auto sm:px-2 md:h-[calc(100vh-20px)]
 lg:py-0 overflow-hidden">
            
        
 <ol class="overflow-hidden space-y-8">
    <li
      class="relative flex-1 after:content-[''] after:w-0.5 after:h-full after:bg-indigo-600 after:inline-block after:absolute after:-bottom-11 after:left-4 lg:after:left-5">
      <a href="https://pagedone.io/" class="flex items-start font-medium w-full">
        <span
          class="w-8 h-8 aspect-square bg-indigo-600 border-2 border-transparent rounded-full flex justify-center items-center mr-3 text-sm text-white lg:w-10 lg:h-10">
          1
        </span>
        <div class="block">
          <h4 class="text-base text-indigo-600 mb-2">كيفية إنشاء حساب؟</h4>
          <p class="text-sm text-gray-600 max-w-xs mb-4">
            لإنشاء حساب، اتبع عدة خطوات لجمع معلومات المستخدم والتحقق من الصلاحيات وإعداد أدوار المستخدم            <br>
          </p>
          <!-- إضافة كود إنشاء الحساب -->
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
  
              <!-- قائمة اختيار حالة المستخدم -->
              <x-select-dropdown 
                id="status" 
                name="status" 
                label="حالة المستخدم"
                :options="['1' => 'فعال', '0' => 'موقوف', '2' => 'قيد الانتظار']"
                :selected="old('status', $user->status ?? '')"
                required 
              />
  
              <!-- زر إرسال -->
              <div class="flex justify-end px-6">
                <x-button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700">
                  إنشاء حساب
                </x-button>
              </div>
            </form>
          </div>
          <!-- نهاية كود إنشاء الحساب -->
        </div>
      </a>
    </li>
    
    <li
    class="relative flex-1 after:content-[''] z-10 after:w-0.5 after:h-full after:z-0 after:bg-gray-200 after:inline-block after:absolute after:-bottom-12 after:left-4 lg:after:left-5">
    <a class="flex items-start font-medium w-full">
      <span
        class="w-8 h-8 bg-indigo-50 relative z-20 border-2 border-indigo-600 rounded-full flex justify-center items-center mr-3 text-sm text-indigo-600 lg:w-10 lg:h-10">2</span>
      <div class="block">
        <h4 class="text-base text-indigo-600 mb-2">تعيين الأدوار للمستخدم</h4>
        <p class="text-sm text-gray-600 max-w-xs">يمكن تعيين أدوار متعددة للمستخدم</p>
        
        <div class="p-6 sm:p-8 space-y-4 md:space-y-6">
          <x-title :title="'تعيين الأدوار للمستخدم'"> </x-title>
          
          @if(session('success'))
            <div class="alert alert-success text-xs sm:text-sm">
              {{ session('success') }}
            </div>
          @endif

          @if(session('error'))
            <div class="alert alert-danger text-xs sm:text-sm">
              {{ session('error') }}
            </div>
          @endif
  
          <form class="space-y-2 md:space-y-2" action="{{ route('users-roles.store') }}" method="POST">
        @csrf
    
        <!-- قائمة اختيار الأدوار -->
        <x-select-dropdown 
          id="roles" 
          name="roles[]" 
          label="الأدوار" 
          :options="$roles->pluck('name', 'id')" 
          :selected="old('roles', $user->roles->pluck('id')->toArray() ?? [])"
          multiple 
          required 
        />
    
        <!-- زر إرسال -->
        <div class="flex justify-end px-6">
            <x-button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700">
                حفظ الأدوار
            </x-button>
        </div>
    </form>
    
        </div>
      </div>
    </a>
</li>

  
 <li class="relative flex-1">
      <a class="flex items-start font-medium w-full">
        <span
          class="w-8 h-8 bg-gray-50 border-2 relative z-10 border-gray-200 rounded-full flex justify-center items-center mr-3 text-sm lg:w-10 lg:h-10">3</span>
        <div class="block">
          <h4 class="text-base text-gray-900 mb-2">How can I reset my password?</h4>
          <p class="text-sm text-gray-600 max-w-xs">
            Go to your profile > Click Change Password > Enter previous password> confirm Previous password and add your new password
          </p>
        </div>
      </a>
    </li>
  </ol>
  
        </div>
    </section>
</x-layout>
