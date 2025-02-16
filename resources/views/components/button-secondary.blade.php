{{-- <button @if($href ?? false) onclick="location.href='{{ $href }}'" @endif
    type="{{ $type }}"
       class="mt-2 sm:mt-0  text-gray-700 bg-green-600 hover:bg-green-700 px-4 py-1 rounded-lg">
    {{ $slot }}
</button> --}}
<button 
    {{ $attributes->merge([
        'type' => $type ?? 'button',
        'class' => ' sm:w-auto h-12 shadow-sm rounded-lg text-base font-semibold leading-7 transition-all duration-300  text-gray-700 bg-green-600 hover:bg-green-700 px-6 py-2 flex items-center justify-center'
    ]) }} 
    @if(isset($onclick)) onclick="{{ $onclick }}" @endif
>
    {{ $slot }}
</button>

