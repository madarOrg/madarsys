<x-layout>
    <x-title :title="'إضافة بيانات الشركة'"></x-title>

        <div class="container mx-auto flex px-5 pt-0 pb-28 md:flex-row flex-col-reverse items-center min-h-screen overflow-hidden">
           
            <!-- Right Side: Form -->
            <div class="lg:flex-grow md:w-2/3 lg:pl-16 md:pl-8 flex flex-col md:items-start md:text-left items-start text-start">
                    <!-- Left Side: Company Image -->
                <form class="w-full" action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">

                    <div class="lg:max-w-lg lg:w-9 md:w-8 w-30 mx-auto md:mx-0">
                        @if(isset($company->logo) && $company->logo !== null)

                            <img class="object-cover object-center rounded w-full h-auto" alt="{{ $company->name }}" src="{{ asset('storage/' . $company->logo) }}">
                        @else
                            <img class="object-cover object-center rounded w-full h-auto" alt="default image" src="https://dummyimage.com/720x600">
                        @endif
                    </div>

                    @csrf <!-- Laravel CSRF Protection -->
                    
                    <div class="flex flex-wrap gap-4 mb-4">
                        <!-- اسم الشركة -->
                        <div class="basis-1/2 shrink-0 max-w-sm">
                            <x-file-input 
                                id="name" 
                                name="name" 
                                label="اسم الشركة" 
                                required="true"
                            />
                        </div>
                    
                        <!-- شعار الشركة باستخدام مكون x-file-input -->
                        <div class="basis-1/2 shrink-0 max-w-sm">
                            <x-file-input 
                                id="logo" 
                                name="logo" 
                                type="file"
                                label="شعار الشركة" 
                            />
                            <p class="text-sm mt-2 text-gray-600 dark:text-gray-400">
                                تم اختيار الملف: {{ session('file_uploaded') }}
                            </p>
                        </div>
                        
                        <!-- رقم الهاتف -->
                        <div class="basis-1/2 shrink-0 max-w-sm">
                            <x-file-input 
                                id="phone_number" 
                                name="phone_number" 
                                label="رقم الهاتف" 
                                required="true"
                            />
                        </div>
                        
                        <!-- البريد الإلكتروني -->
                        <div class="basis-1/2 shrink-0 max-w-sm">
                            <x-file-input 
                                id="email" 
                                name="email" 
                                label="البريد الإلكتروني" 
                                type="email"
                                required="true"
                            />
                        </div>
                    </div>

                    <!-- العنوان -->
                    <div class="mb-4">
                        <x-file-input 
                            id="address" 
                            name="address" 
                            label="العنوان" 
                            required="true"
                        />
                    </div>



                    <!-- معلومات إضافية -->
                    <div class="mb-4">
                        <x-textarea 
        id="additional_info" 
        name="additional_info" 
        label="معلومات إضافية" 
        rows="4" 
    />
   </div>

                    <!-- إعدادات الشركة -->
                    <div class="mb-4">
                        <x-textarea 
                        id="settings" 
                        name="settings" 
                        label="إعدادات الشركة (JSON)"
                        rows="4" 
                    />
                    </div>

                    <!-- زر الإرسال -->
                    <div class=" flex justify-end">
                    <x-button type="submit" >إضافة الشركة</x-button>
                </div>
                </form>
            </div>
        </div>

</x-layout>
