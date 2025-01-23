@props(['href', 'icon', 'text'])

<li class="mt-0.5 w-full ">

    <a href="{{ $href }}" {{ $attributes->merge(['class' => ' flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-100']) }}>
        <i class="fas fa-{{ $icon }} m-2"></i>
        <span class="ms-3  sm:block">{{ $text }}</span>
    </a>
</li>
