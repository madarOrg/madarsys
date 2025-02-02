<button @if($href ?? false) onclick="location.href='{{ $href }}'" @endif
    type="{{ $type }}"
       class="mt-2 sm:mt-0  text-gray-700 bg-green-600 hover:bg-green-700 px-4 py-1 rounded-lg">
    {{ $slot }}
</button>
