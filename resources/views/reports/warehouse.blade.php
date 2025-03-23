<!-- معلومات المستودع -->
<div class="mb-6">
    <x-title :title="' معلومات المستودع'"></x-title>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div>
            <span class="text-gray-600 dark:text-gray-200">الاسم:</span>
            <span class="font-medium">{{ $this->selectedReport->report_data['warehouse_info']['name'] }}</span>
        </div>
        <div>
            <span class="text-gray-600 dark:text-gray-200">الكود:</span>
            <span class="font-medium">{{ $this->selectedReport->report_data['warehouse_info']['code'] }}</span>
        </div>
        <div>
            <span class="text-gray-600 dark:text-gray-200">الفرع:</span>
            <span class="font-medium">{{ $this->selectedReport->report_data['warehouse_info']['branch'] }}</span>
        </div>
        <div>
            <span class="text-gray-600 dark:text-gray-200">المشرف:</span>
            <span class="font-medium">{{ $this->selectedReport->report_data['warehouse_info']['supervisor'] }}</span>
        </div>
        <div>
            <span class="text-gray-600 dark:text-gray-200">المساحة:</span>
            <span class="font-medium">{{ $this->selectedReport->report_data['warehouse_info']['capacity'] }} م²</span>
        </div>
        <div>
            <span class="text-gray-600 dark:text-gray-200">السعة:</span>
            <span class="font-medium">{{ $this->selectedReport->report_data['warehouse_info']['capacity'] }}</span>
        </div>
        <div>
            <span class="text-gray-600 dark:text-gray-200">عدد الرفوف:</span>
            <span class="font-medium">{{ $this->selectedReport->report_data['warehouse_info']['shelves_count'] }}</span>
        </div>
        <div>
            <span class="text-gray-600 dark:text-gray-200">درجة الحرارة:</span>
            <span class="font-medium">{{ $this->selectedReport->report_data['warehouse_info']['temperature'] }}°C</span>
        </div>
        <div>
            <span class="text-gray-600 dark:text-gray-200">الرطوبة:</span>
            <span class="font-medium">{{ $this->selectedReport->report_data['warehouse_info']['humidity'] }}%</span>
        </div>
        <div class="col-span-2 md:col-span-3">
            <span class="text-gray-600 dark:text-gray-200">المناطق التخزينية:</span>
            <div class="mt-2">
                @isset($this->selectedReport->report_data['warehouse_info']['areas'])
                    <ul class="list-disc pl-5">
                        @foreach($this->selectedReport->report_data['warehouse_info']['areas'] as $area)
                            <li class="mt-2">
                                <strong>{{ $area['area_name'] }}</strong>
                                <ul class="list-inside pl-5 mt-1">
                                    @if(!empty($area['shelves']))
                                        @foreach($area['shelves'] as $shelf)
                                            <li>الرف: {{ $shelf['shelf'] }} - الرفوف: {{ $shelf['rack'] }} - الممر: {{ $shelf['aisle'] }}</li>
                                        @endforeach
                                    @else
                                        <li>لا توجد رفوف في هذه المنطقة.</li>
                                    @endif
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">غير متوفر</p>
                @endisset
            </div>
        </div>
    </div>
</div>

<!-- مميزات المستودع -->
<div>
    <x-title :title="' مميزات المستودع'"></x-title>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div>
            <span class="text-gray-600 dark:text-gray-200">مستودع ذكي:</span>
            <span class="font-medium">{{ $this->selectedReport->report_data['features']['is_smart'] ? 'نعم' : 'لا' }}</span>
        </div>
        <div>
            <span class="text-gray-600 dark:text-gray-200">نظام أمني:</span>
            <span class="font-medium">{{ $this->selectedReport->report_data['features']['has_security_system'] ? 'نعم' : 'لا' }}</span>
        </div>
        <div>
            <span class="text-gray-600 dark:text-gray-200">كاميرات مراقبة:</span>
            <span class="font-medium">{{ $this->selectedReport->report_data['features']['has_cctv'] ? 'نعم' : 'لا' }}</span>
        </div>
        <div>
            <span class="text-gray-600 dark:text-gray-200">متكامل مع WMS:</span>
            <span class="font-medium">{{ $this->selectedReport->report_data['features']['is_integrated_with_wms'] ? 'نعم' : 'لا' }}</span>
        </div>
        <div>
            <span class="text-gray-600 dark:text-gray-200">أنظمة آلية:</span>
            <span class="font-medium">{{ $this->selectedReport->report_data['features']['has_automated_systems'] ? 'نعم' : 'لا' }}</span>
        </div>
    </div>
</div>
