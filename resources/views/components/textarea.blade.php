<div class="mb-4">
    <!-- التسمية -->
    @if(($label) && $label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-600 dark:text-gray-400">{{ $label }}</label>
    @endif

    <!-- حقل النص -->
    <textarea 
        id="{{ $id }}" 
        name="{{ $name }}" 
        rows="{{ $rows ?? 4 }}" 
        class="w-full bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:focus:text-gray-200 rounded border border-b text-base outline-none py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
        {{ $attributes }}>{!! old($name, $value ?? '') !!}</textarea>
</div>
