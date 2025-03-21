<div class="py-6 ">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- رأس الصفحة -->
        <div class="overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 border border-gray-950">
            <x-title :title="' تقارير المستودعات'"></x-title>
            @livewireStyles
            @livewireScripts
             
            <!-- نموذج التصفية -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 ">
                <div>
                    <label class="block text-sm font-medium  mb-2">المستودع</label>
                    <select wire:model.live="warehouse_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">اختر المستودع</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium  mb-2">نوع التقرير</label>
                    <select wire:model.live="report_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="details">بيانات المستودع</option>
                        <option value="inventory">تقرير المخزون</option>
                        <option value="movement">تقرير الحركة</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium  mb-2">من تاريخ</label>
                    <input type="date" wire:model.live="date_from" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium  mb-2">إلى تاريخ</label>
                    <input type="date" wire:model.live="date_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            <div class=" flex justify-end">
            <button wire:click="generateReport" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                إنشاء التقرير
            </button>
        </div>
        </div>

        <!-- عرض التقارير -->
        <div class="overflow-hidden shadow-sm sm:rounded-lg border border-gray-950">
            <div class="p-6">
                @if($reports->isEmpty())
                    <div class="text-center">
                        لا توجد تقارير متاحة. الرجاء اختيار مستودع وإنشاء تقرير
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع التقرير</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تم الإنشاء بواسطة</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التفاصيل</th>
                                </tr>
                            </thead>
                            <tbody class=" divide-y divide-gray-200">
                                @foreach($reports as $report)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm ">
                                            {{ $report->report_date }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $report->report_type === 'inventory' ? 'تقرير المخزون' : 'تقرير الحركة' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm ">
                                            {{ $report->generated_by }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm flex gap-2">
                                            <button wire:click="selectReport({{ $report->id }})" class="bg-indigo-600 text-white px-3 py-1.5 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                عرض التفاصيل
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $reports->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- نافذة عرض تفاصيل التقرير -->
        @if($this->selectedReport)
            <div class="fixed inset-0 bg-gray-500  bg-opacity-75 transition-opacity z-50">
                <div class=" fixed inset-0 overflow-y-auto">
                    <div class=" flex min-h-full items-center justify-center p-4 text-center">
                        <div class="  bg-gray-50 dark:bg-gray-900 rounded-lg text-right overflow-hidden shadow-xl transform transition-all w-full max-w-4xl">
                            <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="mb-4">
                                    <x-title :title="'تفاصيل التقرير '"></x-title>

                                    @if($this->selectedReport->report_type === 'details')
                                        <!-- معلومات المستودع -->
                                        <div class="mb-6">
                                            <x-title :title="' معلومات المستودع'"></x-title>
                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                <div>
                                                    <span class="dark:dark:text-gray-200 ">الاسم:</span>
                                                    <span class="font-medium">{{ $this->selectedReport->report_data['warehouse_info']['name'] }}</span>
                                                </div>
                                                <div>
                                                    <span class="dark:text-gray-200">الكود:</span>
                                                    <span class="font-medium">{{ $this->selectedReport->report_data['warehouse_info']['code'] }}</span>
                                                </div>
                                                <div>
                                                    <span class="dark:text-gray-200">الفرع:</span>
                                                    <span class="font-medium">{{ $this->selectedReport->report_data['warehouse_info']['branch'] }}</span>
                                                </div>
                                                <div>
                                                    <span class="dark:text-gray-200">المشرف:</span>
                                                    <span class="font-medium">{{ $this->selectedReport->report_data['warehouse_info']['supervisor'] }}</span>
                                                </div>
                                                <div>
                                                    <span class="dark:text-gray-200">المساحة:</span>
                                                    <span class="font-medium">{{ $this->selectedReport->report_data['warehouse_info']['area'] }} م²</span>
                                                </div>
                                                <div>
                                                    <span class="dark:text-gray-200">السعة:</span>
                                                    <span class="font-medium">{{ $this->selectedReport->report_data['warehouse_info']['capacity'] }}</span>
                                                </div>
                                                <div>
                                                    <span class="dark:text-gray-200">عدد الرفوف:</span>
                                                    <span class="font-medium">{{ $this->selectedReport->report_data['warehouse_info']['shelves_count'] }}</span>
                                                </div>
                                                <div>
                                                    <span class="dark:text-gray-200">درجة الحرارة:</span>
                                                    <span class="font-medium">{{ $this->selectedReport->report_data['warehouse_info']['temperature'] }}°C</span>
                                                </div>
                                                <div>
                                                    <span class="dark:text-gray-200">الرطوبة:</span>
                                                    <span class="font-medium">{{ $this->selectedReport->report_data['warehouse_info']['humidity'] }}%</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- مميزات المستودع -->
                                        <div>
                                            <x-title :title="' مميزات المستودع'"></x-title>
                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                <div>
                                                    <span class="dark:text-gray-200">مستودع ذكي:</span>
                                                    <span class="font-medium">{{ $this->selectedReport->report_data['features']['is_smart'] ? 'نعم' : 'لا' }}</span>
                                                </div>
                                                <div>
                                                    <span class="dark:text-gray-200">نظام أمني:</span>
                                                    <span class="font-medium">{{ $this->selectedReport->report_data['features']['has_security_system'] ? 'نعم' : 'لا' }}</span>
                                                </div>
                                                <div>
                                                    <span class="dark:text-gray-200">كاميرات مراقبة:</span>
                                                    <span class="font-medium">{{ $this->selectedReport->report_data['features']['has_cctv'] ? 'نعم' : 'لا' }}</span>
                                                </div>
                                                <div>
                                                    <span class="dark:text-gray-200">متكامل مع WMS:</span>
                                                    <span class="font-medium">{{ $this->selectedReport->report_data['features']['is_integrated_with_wms'] ? 'نعم' : 'لا' }}</span>
                                                </div>
                                                <div>
                                                    <span class="dark:text-gray-200">أنظمة آلية:</span>
                                                    <span class="font-medium">{{ $this->selectedReport->report_data['features']['has_automated_systems'] ? 'نعم' : 'لا' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($this->selectedReport->report_type === 'inventory')
                                        <!-- تفاصيل المخزون -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <span class="dark:text-gray-200">إجمالي المواد:</span>
                                                <span class="font-medium">{{ $this->selectedReport->report_data['inventory']['total_items'] }}</span>
                                            </div>
                                            <div>
                                                <span class="dark:text-gray-200">المواد منخفضة المخزون:</span>
                                                <span class="font-medium">{{ $this->selectedReport->report_data['inventory']['low_stock_items'] }}</span>
                                            </div>
                                            <div>
                                                <span class="dark:text-gray-200">عدد الفئات:</span>
                                                <span class="font-medium">{{ $this->selectedReport->report_data['inventory']['categories'] }}</span>
                                            </div>
                                            <div>
                                                <span class="dark:text-gray-200">القيمة الإجمالية:</span>
                                                <span class="font-medium">{{ number_format($this->selectedReport->report_data['inventory']['total_value'], 2) }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if($this->selectedReport->report_type === 'movement')
                                        <!-- تفاصيل الحركة -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <span class="dark:text-gray-200">الفترة:</span>
                                                <span class="font-medium">{{ $this->selectedReport->report_data['movements']['period']['from'] }} إلى {{ $this->selectedReport->report_data['movements']['period']['to'] }}</span>
                                            </div>
                                            <div>
                                                <span class="dark:text-gray-200">عدد العمليات الواردة:</span>
                                                <span class="font-medium">{{ $this->selectedReport->report_data['movements']['incoming'] }}</span>
                                            </div>
                                            <div>
                                                <span class="dark:text-gray-200">عدد العمليات الصادرة:</span>
                                                <span class="font-medium">{{ $this->selectedReport->report_data['movements']['outgoing'] }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="px-4 py-3 flex justify-end sm:px-6 sm:flex ">
                                <div class=" rounded-lg  border-green-600 bg-green-600 hover:bg-green-800 dark:hover:bg-green-700 text-gray-700 dark:text-gray-300">
                                <button type="button" wire:click="exportToExcel"
                                    class="inline-flex items-center justify-center  px-4 py-2 text-sm font-semibold  shadow-sm sm:ml-3 sm:w-auto">
                                    <svg class="w-5 h-5 ml-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    تصدير Excel
                                </button>
                            </div>
                            <div class=" mr-2 rounded-lg  border-blue-600 bg-blue-600 hover:bg-blue-800 dark:hover:bg-blue-700 text-gray-700 dark:text-gray-300">
                                <button type="button" wire:click="exportToPdf"
                                    class="inline-flex items-center justify-center   px-4 py-2 text-sm font-semibold  shadow-sm  sm:ml-3 sm:w-auto">
                                    <svg class="w-5 h-5 ml-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    تصدير PDF
                                </button>

                            </div>
                            <div class="mr-2 rounded-lg border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900  hover:text-gray-200 transition-all duration-700  text-gray-700 dark:text-gray-400 text-base font-semibold leading-7">
                                <button type="button" wire:click="selectReport(null)"
                                    class="inline-flex items-center justify-center   px-4 py-2 text-sm font-semibold    sm:mt-0  sm:w-auto">
                                    إغلاق
                                </button>
                            </div>
                            </div>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

       
    </div>
</div>
