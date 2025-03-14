<div x-data="searchableSelect()" class="relative inline-block w-full">
    <!-- زر العرض (يعرض القيمة المختارة أو نص "اختر") -->
    <button @click="toggle()" type="button" class="w-full border px-3 py-2 rounded-md text-left" :class="{'border-blue-500': open}">
        <span x-text="selected ? selected.name : 'اختر'"></span>
    </button>

    <!-- القائمة المنبثقة -->
    <div x-show="open" class="absolute z-10 mt-1 w-full bg-white border rounded-md shadow-lg" @click.away="close()">
        <!-- حقل البحث داخل القائمة -->
        <input type="text" x-model="search" @input="fetchOptions()" placeholder="ابحث..." class="w-full px-3 py-2 border-b focus:outline-none">
        <ul class="max-h-60 overflow-auto">
            <template x-for="option in filteredOptions" :key="option.id">
                <li @click="select(option)" class="cursor-pointer hover:bg-gray-100 px-3 py-2" x-text="option.name"></li>
            </template>
        </ul>
    </div>

    <!-- حقل مخفي لإرسال القيمة المحددة -->
    <input type="hidden" name="{{ $name }}" :value="selected ? selected.id : ''">
</div>

<script>
    function searchableSelect() {
        return {
            open: false,  // جعل القائمة مغلقة بشكل افتراضي
            search: '',  // حقل البحث فارغ في البداية
            selected: null,  // لا توجد قيمة مختارة في البداية
            options: @json($products),  // تحميل جميع المنتجات عند بدء الصفحة
            route: '{{ url('/api/search/products') }}', // استخدام المسار الممرر من المكون (من Blade)

            toggle() {
                this.open = !this.open; // التبديل بين فتح وغلق القائمة
            },

            close() {
                this.open = false; // إغلاق القائمة عند النقر خارجها
            },

            select(option) {
                this.selected = option; // تحديد الخيار المختار
                this.search = ''; // مسح حقل البحث بعد اختيار العنصر
                this.close(); // إغلاق القائمة بعد الاختيار
            },

            fetchOptions() {
                // جلب الخيارات عندما يكون هناك نص طويل في حقل البحث
                if (this.search.length > 1) {
                    fetch(`${this.route}?query=${this.search}`, {
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            this.options = data;  // تحديث الخيارات بناءً على البيانات المسترجعة
                        })
                        .catch(error => console.error('Error fetching options:', error));
                }
            },

            get filteredOptions() {
                // تصفية الخيارات بناءً على النص المدخل
                if (this.search.length > 1) {
                    return this.options.filter(option => 
                        option.name.toLowerCase().includes(this.search.toLowerCase())
                    );
                } else {
                    return this.options;  // عرض جميع الخيارات إذا كان البحث فارغًا أو يحتوي على نص قصير
                }
            }
        }
    }
</script>
