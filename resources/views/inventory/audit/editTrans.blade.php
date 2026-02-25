<x-layout>
    <div class="p-6">

      <form id="transaction-view-form" method="POST"
            action="{{ route('inventory.audit.updateTrans', $selectedTransaction->id) }}">
        @csrf
        @method('PUT')
  
        {{-- عنوان الصفحة --}}
        <x-title :title="'تعديل جرد #' . $selectedTransaction->inventory_code" class="mb-6" />
  
        {{-- بيانات الحركة --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
          <div>
            <label class="block text-sm font-medium text-gray-700">نوع العملية</label>
            <input type="text" readonly class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                   value="{{ $selectedTransaction->transactionType->name }}">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">النوع الفرعي</label>
            <input type="text" readonly class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                   value="{{ $selectedTransaction->subType->name }}">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">تاريخ العملية</label>
            <input type="text" readonly class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                   value="{{ optional($selectedTransaction->transaction_date)->format('Y-m-d H:i') }}">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">الرقم المرجعي</label>
            <input type="text" readonly class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                   value="{{ $selectedTransaction->reference }}">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">الشريك</label>
            <input type="text" readonly class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                   value="{{ $selectedTransaction->partner->name }}">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">المستودع</label>
            <input type="text" readonly class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1"
                   value="{{ $selectedTransaction->warehouse->name }}">
          </div>
        </div>
  
        {{-- تفاصيل الأصناف --}}
        <div class="overflow-auto mb-6">
          {{-- <table id="audit-items-table" class="w-full text-right"> --}}
            <table class="audit-items-table w-full text-sm text-right text-gray-500 dark:text-gray-400">
              <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                  <tr>
                <th class="p-2">المنتج</th>
                <th class="p-2">الوحدة</th>
                <th class="p-2">الكمية</th>
                <th class="p-2">المتوقعة</th>
                <th class="p-2">السعر</th>
                <th class="p-2">الإجمالي</th>
                <th class="p-2">حذف</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($items as $idx => $item)
                <tr data-index="{{ $idx }}" class="bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">

                  <input type="hidden" name="items[{{ $idx }}][id]" value="{{ $item->id }}">
                  <td class="p-2">
                    {{ $item->product->name }}- {{ $item->product->barcode }}- {{ $item->product->sku }}
                    <input type="hidden" name="items[{{ $idx }}][product_id]" value="{{ $item->product_id }}">
                    {{-- <input type="hidden" name="items[{{ $idx }}][barcode]" value="{{ $item->product->barcode ?? '-' }}"> --}}
                </td>
                
                  </td>
                  <td class="p-2">
                    {{ $item->unit->name }}
                    <input type="hidden" name="items[{{ $idx }}][unit_id]" value="{{ $item->unit_id }}">
                  </td>
                  <td class="p-2">
                    <input type="number"
                           name="items[{{ $idx }}][quantity]"
                           value="{{ $item->quantity }}"
                           class="quantity-inputw-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" />
                  </td>
                  <td class="p-2">
                    <input type="number"
                           name="items[{{ $idx }}][expected]"
                           value="{{ $item->expected_audit_quantity }}"
                           class="expected-inputw-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" 
                           readonly 
                           />
                  </td>
                  <td class="p-2">
                    <input type="number"
                           name="items[{{ $idx }}][unit_price]"
                           value="{{ $item->unit_price  }}"
                           class="price-inputw-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" />
                  </td>
                  <td class="p-2 total-cell">
                    {{ number_format($item->quantity * $item->price, 2) }}
                  </td>
                  <td class="p-2">
                    <button type="submit" class="remove-btn text-red-600 hover:text-red-800">
                      <i class="fas fa-trash-alt"></i>
                  </button>
                    {{-- <button type="button" class="remove-btn text-red-600">×</button> --}}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
  
        <div class="mt-4 flex justify-between">
          <button type="button" id="add-item" class="px-4 py-2 bg-green-500 border  dark:text-white text-gray-600 rounded">
            إضافة صنف
          </button>
          <button type="submit" class="px-4 py-2 bg-blue-600 border dark:text-white text-gray-600 rounded">
            حفظ الجرد
          </button>
        </div>
  
        <p id="save-status" class="mt-2 text-sm text-gray-600"></p>
      </form>
    </div>
  </x-layout>
  
  <script>
    const products = @json($products);
    const units    = @json($units);
  
    function recalcRow(row) {
      const q = parseFloat(row.querySelector('.quantity-input').value) || 0;
      const p = parseFloat(row.querySelector('.price-input').value)    || 0;
      row.querySelector('.total-cell').innerText = (q * p).toFixed(2);
    }
  
    function saveAjax() {
      const form    = document.getElementById('transaction-view-form');
      const status  = document.getElementById('save-status');
      status.textContent = 'جارٍ الحفظ…';
      axios.post(form.action, new FormData(form))
        .then(() => status.textContent = 'تم الحفظ ✔️')
        .catch(() => status.textContent = '❌ فشل الحفظ');
    }
  
    document.getElementById('audit-items-table').addEventListener('click', e => {
      if (e.target.classList.contains('remove-btn')) {
        e.target.closest('tr').remove();
        saveAjax();
      }
    });
  
    // Trigger on both input and change for quantity and price
    document.getElementById('audit-items-table').addEventListener('input', e => {
      if (e.target.classList.contains('quantity-input') || e.target.classList.contains('price-input')) {
        const row = e.target.closest('tr'); recalcRow(row); saveAjax();
      }
    });
    document.getElementById('audit-items-table').addEventListener('change', e => {
      if (e.target.classList.contains('price-input')) {
        const row = e.target.closest('tr'); recalcRow(row); saveAjax();
      }
    });
  
    document.getElementById('add-item').addEventListener('click', () => {
      const tbody = document.querySelector('#audit-items-table tbody');
      const idx   = tbody.children.length;
      const tr    = document.createElement('tr');
      tr.dataset.index = idx; tr.classList.add('border-b');
      tr.innerHTML = `
        <input type="hidden" name="items[${idx}][id]" value="0">
       <input type="hidden" name="items[${idx}][id]" value="0">
    <td class="p-2">
      <select name="items[${idx}][product_id]" required class="border rounded px-1 w-32">
        <option value="">اختر منتج</option>
        ${products.map(p => `<option value="${p.id}">${p.name}</option>`).join('')}
      </select>
    </td>
        <td class="p-2">
          <select name="items[${idx}][unit_id]" required class="border rounded px-1 w-24">
            <option value="">اختر وحدة</option>
            ${units.map(u => `<option value="${u.id}">${u.name}</option>`).join('')}
          </select>
        </td>
        <td class="p-2">
          <input type="number" name="items[${idx}][quantity]" value="0" class="quantity-inputw-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" />
        </td>
        <td class="p-2">
          <input type="number" name="items[${idx}][expected]" value="0" class="expected-inputw-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" />
        </td>
        <td class="p-2">
          <input type="number" name="items[${idx}][unit_price]" value="0" class="price-inputw-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" />
        </td>
        <td class="p-2 total-cell">0.00</td>
        <td class="p-2">
          <button type="button" class="remove-btn text-red-600">×</button>
        </td>
      `;
      tbody.appendChild(tr);
    });
  </script>
