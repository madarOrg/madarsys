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
           <span
               class="font-medium">{{ number_format($this->selectedReport->report_data['inventory']['total_value'], 2) }}</span>
       </div>
   </div>
