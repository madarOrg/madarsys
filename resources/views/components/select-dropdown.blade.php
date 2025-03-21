{{-- <div class="mb-4">
    <!-- التسمية -->
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-600 dark:text-gray-400">{{ $label }}</label>
    <div class="mt-2">
        <select name="{{ $name }}" id="{{ $id }}" class="w-full bg-gray-100 rounded border border-b  dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500  dark:focus:text-gray-200 mt-1">

            @foreach($options as $key => $value)
                <option value="{{ $key }}" {{ $selected == $key ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div> --}}
@props(['id', 'name', 'label', 'options' => [], 'selected' => null])

<div class="mb-4">
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-600 dark:text-gray-400">
        {{ $label }}
    </label>
    <div class="mt-2">
        <select name="{{ $name }}" id="{{ $id }}" 
            class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 
                   hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors 
                   duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1">
            <option value="">اختر {{ $label }}</option>
            @foreach($options as $key => $value)
                <option value="{{ $key }}" {{ (string) $selected === (string) $key ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>
</div>
{{-- @props(['id', 'name', 'label', 'options' => [], 'selected' => null, 'tomSelect' => false, 'multiple' => false, 'create' => 'false'])

<div class="mb-4">
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-600 dark:text-gray-400">
        {{ $label }}
    </label>
    <div class="mt-2">
        <select 
            name="{{ $name }}" 
            id="{{ $id }}" 
            @if($multiple) multiple @endif
            @if($tomSelect) class="tom-select" data-create="{{ $create }}" placeholder="اختر {{ $label }}" @else class="w-full bg-gray-100 rounded border border-b dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out dark:focus:bg-gray-700 focus:outline-blue-500 dark:focus:text-gray-200 mt-1" @endif
        >
            <option value="">اختر {{ $label }}</option>
            @foreach($options as $key => $value)
                <option value="{{ $key }}" {{ (string) $selected === (string) $key ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>
</div>
 --}}
