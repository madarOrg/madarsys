<div class="flex items-center">
    <input
        type="checkbox"
        id="{{ $id }}"
        name="{{ $name }}"
        @if($checked) checked @endif
        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
    />
    <label for="{{ $id }}" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
        {{ $label }}
    </label>
</div>

