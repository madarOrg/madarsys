<x-layout>
    <div class="container mx-auto px-4 py-6">
        {{-- <h1 class="text-2xl font-bold mb-6">لوحة التحكم</h1> --}}
        <form method="GET" action="{{ route('dashboard') }}">
            <div class="mb-4">
                <label for="warehouse_id" class="form-label">اختر المستودع:</label>
                <select name="warehouse_id" id="warehouse_id" class="form-select tom-select" onchange="this.form.submit()">
                    @foreach($accessibleWarehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ $warehouse->id == $selectedWarehouseId ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- مخطط Pie لعدد المنتجات حسب التصنيفات --}}
            <div class="bg-white  shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">توزيع المنتجات حسب التصنيفات</h2>
                <div id="categoryChart"></div>
            </div>

            {{-- مخطط عمودي لعدد المنتجات المخزنة لكل تصنيف --}}
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">كمية المنتجات المخزنة حسب التصنيف</h2>
                <div id="barChart"></div>
            </div>

            {{-- مخطط خطي لعدد الحركات المدخلة والمخرجة حسب الأشهر --}}
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">حركات المخزون حسب الأشهر</h2>
                <div id="lineChart"></div>
            </div>

            {{-- مخطط دائري لحالة المخزون (مخزن / مفقود) --}}
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">حالة المخزون</h2>
                <div id="donutChart"></div>
            </div>

            {{-- مخطط تكدس لعرض حركات المخزون المدخلة والمخرجة حسب التصنيفات --}}
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">حركات المخزون حسب التصنيف</h2>
                <div id="stackedBarChart"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- استدعاء مكتبة ApexCharts --}}
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        {{-- مخطط Pie --}}
        <script>
            var options = {
                chart: { type: 'pie', height: 350 },
                series: @json($categoryCounts),
                labels: @json($categoryLabels),
                title: { text: "المنتجات حسب التصنيفات", align: 'center' }
            };
            var chart = new ApexCharts(document.querySelector("#categoryChart"), options);
            chart.render();
        </script>

        {{-- مخطط عمودي --}}
        <script>
            var barOptions = {
                chart: { type: 'bar', height: 350 },
                series: [{ name: 'المنتجات المخزنة', data: @json($categoryCounts) }],
                xaxis: { categories: @json($categoryLabels), title: { text: 'التصنيفات' } },
                yaxis: { title: { text: 'العدد' } },
                title: { text: "كمية المنتجات المخزنة حسب التصنيف", align: 'center' }
            };
            var barChart = new ApexCharts(document.querySelector("#barChart"), barOptions);
            barChart.render();
        </script>

        {{-- مخطط خطي --}}
        <script>
            var lineOptions = {
                chart: { type: 'line', height: 350 },
                series: [{
                    name: 'حركات مدخلة',
                    data: @json($inputData)
                }, {
                    name: 'حركات مخرجة',
                    data: @json($outputData)
                }],
                xaxis: { categories: @json($months), title: { text: 'الشهر' } },
                yaxis: { title: { text: 'عدد الحركات' } },
                title: { text: "حركات المخزون حسب الأشهر", align: 'center' },
                dataLabels: { enabled: false },
                legend: { position: 'top' }
            };
            var lineChart = new ApexCharts(document.querySelector("#lineChart"), lineOptions);
            lineChart.render();
        </script>

        {{-- مخطط دائري --}}
        <script>
            var donutOptions = {
                chart: { type: 'donut', height: 350 },
                series: [@json($storedCount), @json($missingCount)],
                labels: ['مخزن', 'مفقود'],
                title: { text: "حالة المخزون", align: 'center' }
            };
            var donutChart = new ApexCharts(document.querySelector("#donutChart"), donutOptions);
            donutChart.render();
        </script>

        {{-- مخطط تكدس --}}
        <script>
            var stackedBarOptions = {
                chart: { type: 'bar', height: 350 },
                series: [{
                    name: 'حركات مدخلة',
                    data: @json($inputData)
                }, {
                    name: 'حركات مخرجة',
                    data: @json($outputData)
                }],
                xaxis: { categories: @json($categoryLabels), title: { text: 'التصنيفات' } },
                yaxis: { title: { text: 'عدد الحركات' } },
                title: { text: "حركات المخزون حسب التصنيف", align: 'center' },
                stacked: true
            };
            var stackedBarChart = new ApexCharts(document.querySelector("#stackedBarChart"), stackedBarOptions);
            stackedBarChart.render();
        </script>
    @endpush
</x-layout>
