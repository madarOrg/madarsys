<button @if($href ?? false) onclick="location.href='{{ $href }}'" @endif
    type="{{ $type }}"
    class="inline-flex items-center pr-2 pl-2 py-2 text-gray-900 bg-gray-100 border-2 border-gray-400 dark:text-gray-400 dark:bg-gray-800 dark:border-gray-700 rounded-md hover:bg-blue-600 dark:hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
    >
    {{ $slot }}
</button>
