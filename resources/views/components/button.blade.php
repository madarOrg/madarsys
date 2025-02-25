@props(['type' => 'submit', 'href' => null])

<button 
    @if($href) onclick="location.href='{{ $href }}'" @endif
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'w-52 h-12 shadow-sm rounded-lg border-indigo-600 bg-indigo-600 dark:hover:bg-indigo-800 hover:bg-indigo-900 hover:text-gray-200 transition-all duration-700 text-gray-700 dark:text-gray-400 text-base font-semibold leading-7'
    ]) }}>
    {{ $slot }}
</button>
