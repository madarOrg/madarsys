<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
    dir="rtl"
    >
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="user-id" content="{{ auth()->id() }}">
        {{-- <title>{{ config('app.name') }}</title> --}}
    
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&display=swap" rel="stylesheet">
        <link href="https://fonts.bunny.net/css?family=tajawal:300,400,500,700,900&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <!-- Tom Select -->
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <!-- Popper.js -->
        <script src="https://unpkg.com/@popperjs/core@2"></script>
    
        <script>
            window.Laravel = {!! json_encode(['userId' => auth()->id()]) !!};
        </script>
        
        
 {{-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script> --}}
{{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://unpkg.com/@alpinejs/livewire@3.x.x/dist/cdn.min.js"></script> --}}

 
 {{-- Nepcha Analytics --}}
 {{-- <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script> --}}
{{-- Apple Touch Icon --}}
{{-- <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}" /> --}}
{{-- Favicon --}}
{{-- <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}" /> --}}

<script>
    window.Laravel = {!! json_encode(['userId' => auth()->id()]) !!};
</script>

@vite([
    'resources/css/app.css',
    'resources/js/app.js',

    // 'resources/js/notifications.js',

    // 'resources/css/nucleo-icons.css',
    // 'resources/css/nucleo-svg.css',
    'resources/css/soft-ui-dashboard-tailwind.css',
    // 'units_products.js',

    // 'resources/js/inventory.js',
    // 'resources/js/plugins/chartjs.min.js', //<!-- Plugin for Charts -->
    // 'resources/js/plugins/perfect-scrollbar.min.js', //Plugin for Scrollbar
    // 'resources/js/soft-ui-dashboard-tailwind.js',// <!-- Main Script File -->
])
@livewireStyles
 
 <!-- GitHub Button -->
 <script async defer src="https://buttons.github.io/buttons.js"></script>
 
    </head>
    <body 
    class="font-sans antialiased leading-default     bg-gray-50 dark:bg-gray-900 text-black dark:text-white" style="font-family: 'Tajawal', sans-serif;">
  
    {{-- <body class="m-0 font-sans antialiased font-normal text-base leading-default bg-gray-50  dark:bg-gray-900 text-black dark:text-white"> --}}
        {{-- <body class="antialiased bg-white dark:bg-gray-900 text-black dark:text-white"> --}}

        {{ $slot }}
        @livewireScripts
        @stack('scripts')

    </body>

</html>
