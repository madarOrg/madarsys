{{-- <div class="flex items-center">
    <input
        type="checkbox"
        id="{{ $id }}"
        name="{{ $name }}"
        @if($checked) checked @endif
        class="w-4 h-4 rounded-md bg-white text-gray-900 focus:outline focus:outline-2 focus:outline-indigo-600 sm:text-sm"
    />
    <label for="{{ $id }}" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
        {{ $label }}
    </label>
</div> --}}
@props(['id', 'name', 'label', 'checked' => false])

<div class="flex items-center">
    <input
        type="checkbox"
        id="{{ $id }}"
        name="{{ $name }}"
        @if($checked) checked @endif
        class="w-4 h-4 rounded-md bg-white text-gray-900 focus:outline focus:outline-2 focus:outline-indigo-600 sm:text-sm"
    />
    <label for="{{ $id }}" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
        {{ $label }}
    </label>
</div>


