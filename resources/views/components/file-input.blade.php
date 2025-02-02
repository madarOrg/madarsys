
<div class="mb-2">
    <!-- التسمية -->
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-600 dark:text-gray-400">{{ $label }}</label>

    <!-- حقل تحميل الملف -->
    <input type="{{ $type ?? 'text' }}" 
           id="{{ $id }}" 
           name="{{ $name }}" 
           class="w-full bg-gray-100 rounded border border-b  dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500  dark:focus:text-gray-200 mt-1"
           {{ $attributes }} />
           
   @if (session('file_uploaded'))
        <p class="text-sm mt-2 text-gray-600 dark:text-gray-400">
            تم اختيار الملف: {{ session('file_uploaded') }}
        </p>
    @endif
    

</div>
