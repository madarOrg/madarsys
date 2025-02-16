<x-layout>
  <section>
    <div class="container">
      <div class="relative">
        
          <x-title :title="'  جميع المنتجات '"></x-title>
         
  
       
      </div>
      <div class="relative overflow-auto shadow-md sm:rounded-lg mt-6">
        <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-6 py-3">الصورة</th>
              <th scope="col" class="px-6 py-3">اسم المنتج</th>
              <th scope="col" class="px-6 py-3">التصنيف</th>
              <th scope="col" class="px-6 py-3">المورد</th>
              <th scope="col" class="px-6 py-3">سعر الشراء</th>
              <th scope="col" class="px-6 py-3">سعر البيع</th>
              <th scope="col" class="px-6 py-3">المخزون</th>
              <th scope="col" class="px-6 py-3">الحالة</th>
              <th scope="col" class="px-6 py-3">الإجراء</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($products as $product)
              <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="p-4">
                  <img src="{{ asset('storage/' . $product->image) }}" class="w-16 md:w-24 rounded-md object-cover" alt="{{ $product->name }}">
                </td>
                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                  {{ $product->name }}
                </td>
                <td class="px-6 py-4">
                  {{ $product->category->name }}
                </td>
                <td class="px-6 py-4">
                  {{ optional($product->supplier)->name ?? 'غير متوفر' }}
                </td>
                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                  ${{ number_format($product->purchase_price, 2) }}
                </td>
                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                  ${{ number_format($product->selling_price, 2) }}
                </td>
                <td class="px-6 py-4">
                  {{ $product->stock_quantity }} {{ $product->unit }}
                </td>
                <td class="px-6 py-4">
                  <span class="px-2 py-1 text-xs font-medium rounded-md {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $product->is_active ? 'متاح' : 'غير متاح' }}
                  </span>
                </td>
                <td class="px-6 py-4">
                 
                 <a href="{{ route('products.show', $product->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">عرض</a>
                 
                 <a href="{{ route('products.edit', $product->id) }}" class="text-blue-600 hover:underline dark:text-blue-500">
                    <i class="fa-solid fa-pen"></i>
                </a>
                <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: none;">
                  @csrf
                  @method('DELETE')
              </form>
              
              <button onclick="confirmDelete({{ $product->id }})" class="text-red-600 hover:text-red-800">
                <i class="fas fa-trash-alt"></i>
              </button>
              
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <x-pagination-links :paginator="$products" />

    </div>

  </section>

</x-layout>
