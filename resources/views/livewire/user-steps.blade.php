<div>
    <!-- عرض الرسائل الناجحة -->
    {{-- @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif --}}

    <ol class="pt-12 flex items-center w-full text-sm text-gray-500 font-medium sm:text-base mb-12">
        <!-- Step 1 -->
        <li wire:click="goToStep(1)"
            class="cursor-pointer flex md:w-full items-center {{ $currentStep >= 1 ? 'text-indigo-600' : 'text-gray-600' }} sm:after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 after:border-1 after:hidden sm:after:inline-block after:mx-4 xl:after:mx-8">
            <div class="flex items-center whitespace-nowrap after:content-['/'] sm:after:hidden after:mx-2">
                <span
                    class="m-4 w-6 h-6 {{ $currentStep >= 1 ? 'bg-indigo-600 border border-indigo-200' : ' bg-gray-100 border border-gray-200' }} rounded-full flex justify-center items-center mr-3 text-sm text-white lg:w-10 lg:h-10">1</span>
                إنشاء مستخدم
            </div>
        </li>

        <!-- Step 2 -->
        <li wire:click="goToStep(2)"
            class="cursor-pointer flex md:w-full items-center {{ $currentStep >= 2 ? 'text-indigo-600' : 'text-gray-600' }} sm:after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 after:border-1 after:hidden sm:after:inline-block after:mx-4 xl:after:mx-8">
            <div class="flex items-center whitespace-nowrap after:content-['/'] sm:after:hidden after:mx-2">
                <span
                    class="m-4 w-6 h-6 {{ $currentStep >= 2 ? 'bg-indigo-600 text-white border border-indigo-200' : ' bg-gray-100 border border-gray-200' }} rounded-full flex justify-center items-center mr-3  lg:w-10 lg:h-10">2</span>
                إضافة أدوار المستخدم
            </div>
        </li>
        {{-- <!-- Step 3 -->
    <li wire:click="goToStep(3)"
        class="cursor-pointer flex md:w-full items-center {{ $currentStep >= 3 ? 'text-indigo-600' : 'text-gray-600' }} sm:after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 after:border-1 after:hidden sm:after:inline-block after:mx-4 xl:after:mx-8">
        <div class="flex items-center whitespace-nowrap after:content-['/'] sm:after:hidden after:mx-2">
            <span
                class="m-4 w-6 h-6 {{ $currentStep >= 3 ? 'bg-indigo-600 text-white border border-indigo-200' : ' bg-gray-100 border border-gray-200' }} rounded-full flex justify-center items-center mr-3 lg:w-10 lg:h-10">3</span> إضافة
               صلاحيات المستخدم
        </div>
    </li> --}}


    </ol>

    <!-- Form starts here -->
    <div class="flex flex-col">
        <!-- Step 1: User Info -->
        @if ($currentStep == 1)
            <form wire:submit.prevent="createUser">
                <div class=" grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="">
                        <x-file-input type="text" id="name" name="name" wire:model="name" label="الإسم"
                            required />
                        @error('name')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="">
                        <x-file-input type="email" id="email" name="email" wire:model="email" label="الإيميل"
                            required />
                        @error('email')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class=" grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="">
                        <x-file-input type="password" id="password" name="password" wire:model="password"
                            label="كلمة المرور" required />
                        @error('password')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="">
                        <x-file-input type="password" id="password_confirmation" name="password_confirmation"
                            wire:model="password_confirmation" label="تأكيد كلمة المرور" required />
                        @error('password_confirmation')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class= "flex justify-end ">
                    <button type="submit" wire:click="nextStep"
                        class="w-52 h-12 shadow-sm rounded-lg border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900  hover:text-gray-200 transition-all duration-700  text-gray-700 dark:text-gray-400 text-base font-semibold leading-7">
                        التالي
                    </button>
                </div>
            </form>
        @endif

        @if ($currentStep == 2)
        <div class="flex flex-col justify-between items-start">
            <form wire:submit.prevent="assignRoles">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">اختر دور</label>
                    <select wire:model="selectedRole"
                        class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1">
                        <option value="">اختر دور</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedRole')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <!-- عرض الأدوار الحالية داخل إطار -->
                
                    @if (!empty($userRoles))
                    <div class="mt-6 border border-gray-300 dark:border-gray-700 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">الأدوار الحالية:
                        </h3>
                        <ul>
                            @foreach ($userRoles as $role)
                                <li wire:key="role-{{ $role['id'] }}"
                                    class="flex items-center text-gray-700 mb-2">
                                    <span class="mr-2">{{ $role['name'] }}</span>
                                    <button type="button" wire:click="removeRole({{ $role['id'] }})"
                                        class="text-red-500 hover:text-red-700 text-xl font-bold">
                                        x
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            
            </div>
                <div class="flex justify-end mb-4">
                    <button type="submit"
                        class="w-52 h-12 shadow-sm rounded-lg border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900 hover:text-gray-200 transition-all duration-700 text-gray-700 dark:text-gray-400 text-base font-semibold leading-7">
                        إضافة
                    </button>
                    <button type="button" wire:click="goToStep(1)"
                        class="w-52 h-12 shadow-sm rounded-lg border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900 hover:text-gray-200 transition-all duration-700 text-gray-700 dark:text-gray-400 text-base font-semibold leading-7 ml-2">
                        رجوع
                    </button>
                </div>
            </form>


        </div>
    @endif

       


    </div>
</div>
